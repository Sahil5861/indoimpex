<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\Gallery;


class GalleryController extends Controller
{
    public function viewfolders(){
        $folders = Folder::where('deleted_at', null)->get();
        return view('admin.pages.gallery.index', compact('folders'));
    }

    public function viewfolderImages(Request $request ,$id){
        $folder = Folder::find($id);
        $limit = $request->input('limit', 12);
        $offset = $request->get('offset', 0);

        if ($limit) {
            $images = Gallery::where('folder_id', $id)->skip($offset)->take($limit)->get();
            if ($request->ajax()) {
                return response()->json($images);
            }
        }
        return view('admin.pages.gallery.images', compact('folder', 'images'));
    }

    public function uploadImages(Request $request){
        $validate = $request->validate([
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if (!$validate) {
            return back()->withErrors(['error' => 'Something went wrong with one of the images!']);
        }
        
        $folder = Folder::where('id', $request->folder_id)->first();

        // Product Image store Handle : 
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image){
                // Generate a unique name for each image
                $imageName = time() . '.' . $image->getClientOriginalName();
                if ($folder->name == 'Product') {
                    $image->move(public_path('uploads/' . $folder->name . '/' . 'original'), $imageName);
                    $imagepath = 'uploads/' . $folder->name . '/' . 'original/' . $imageName;
                }
                else{
                    $image->move(public_path('uploads/'.$folder->name), $imageName);
                    $imagepath = 'uploads/'.$folder->name.'/' . $imageName;
                }

                $gallery_image = new Gallery();
                $gallery_image->folder_id = $folder->id;
                $gallery_image->image_path = $imagepath;

                if ($gallery_image->save()) {
                    return redirect()->route('admin.gallery.image', $folder->id)->with('success', 'Images Uploaded Successfully!');
                }
                return back()->withErrors(['error' => 'Something went wrong with one of the images!']);
            }
        }
        else{
            return response()->json(['status' => 'error', 'message' => 'No images were uploaded.'], 400);  
        }                    
    }

    public function deleteImage($id) {
    $image = Gallery::findOrFail($id);
    $imagePath = $image->image_path; // Full path to the image file
    
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Delete the image record from the database
    $image->delete();

    return response()->json(['success' => true]);
}

}
