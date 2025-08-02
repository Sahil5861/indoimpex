<?php

use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColourController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductGroupsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RoleController2;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DealersController;
use App\Http\Controllers\ViewsController;
use App\Http\Controllers\ContactPersonController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdditionalImageController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BoppCategoryController;
use App\Http\Controllers\BoppItemController;

use App\Http\Controllers\PPWovenCategoryController;
use App\Http\Controllers\PPWovenItemController;
use App\Http\Controllers\StocksBoppController;
use App\Http\Controllers\MasterAjaxController;





use App\Http\Controllers\NonWovenCategoryController;
use App\Http\Controllers\NonWovenItemController;
use App\Http\Controllers\MainUserController;
use App\Http\Controllers\PartyController;

use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\JobdetailsController;
use App\Http\Controllers\JobTypesController;
use App\Http\Controllers\JobNamesController;
use App\Http\Controllers\OrderBookconteroller;
use App\Http\Controllers\ActivityLogs;

// Dealersauth routes
use App\Http\Controllers\DealerAuthController;

// Public Routes
Route::get('/', function () {
    if (!Auth::check()) {        
        return view('auth.login');
    }
    return redirect()->route('dashboard');
})->name('home');


Route::get('/states/{id}', [LocationController::class, 'getStates'])->name('getStates');
Route::get('/cities/{id}', [LocationController::class, 'getCities'])->name('getCities');



// Authentication Routes
Route::middleware(['redirectIfAuthenticated'])->group(function () {
    Route::get('/login', function (){
        return view('auth.login');
    })->name('login');
    Route::get('/register', [RegisterUserController::class, 'showRegistrationForm'])->name('register');
    Route::post('/login', [LoginUserController::class, 'login']);
    Route::post('/register', [RegisterUserController::class, 'register']);
});

Route::post('update-password', [UserController::class, 'updatePassword'])->name('updatePassword');
Route::post('/change-status', [UserController::class, 'changeStatus'])->name('change-user-status');

Route::get('/activity-logs', [ActivityLogs::class, 'index'])->name('admin.activitylogs.view');
Route::get('/activity-logs-delete/{id}', [ActivityLogs::class, 'destroy'])->name('admin.activitylogs.delete');

// Protected Routes of Admin
// Route::middleware(['auth', 'can:users'])->group(function () {

Route::middleware(['auth'])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');

    Route::post('/logout', [LoginUserController::class, 'logout'])->name('logout');
    Route::get('/logout', [LoginUserController::class, 'logout'])->name('logout.get');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('admin/user', [UserController::class, 'index'])->name('admin.user');
    Route::post('admin/user/update-status/{id}', [UserController::class, 'updateStatus'])->name('admin.user.status');
    Route::get('admin/user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::get('admin/user/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::get('admin/user/delete/{id}', [UserController::class, 'remove'])->name('admin.user.delete');
    Route::delete('admin/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
    Route::post('admin/user/create', [UserController::class, 'store'])->name('admin.user.create.post');
    Route::put('admin/user/{user}', [UserController::class, 'update'])->name('admin.user.edit.post');
    Route::delete('admin/user/delete-selected', [UserController::class, 'deleteSelected'])->name('admin.user.deleteSelected');
    Route::post('admin/user/import', [UserController::class, 'import'])->name('admin.user.import');
    Route::get('admin/user/export', [UserController::class, 'export'])->name('admin.user.export');
    Route::get('/sample-file-download-user', [UserController::class, 'sampleFileDownloadUser'])->name('sample-file-download-user');


    // Roles
    Route::get('admin/role', [RoleController::class, 'index'])->name('admin.role');
    Route::post('admin/role/update-status/{id}', [RoleController::class, 'updateStatus'])->name('admin.role.status');
    Route::get('admin/role/create', [RoleController::class, 'create'])->name('admin.role.create');
    Route::get('admin/role/edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
    Route::get('admin/role/delete/{id}', [RoleController::class, 'remove'])->name('admin.role.delete');
    Route::post('admin/role/create', [RoleController::class, 'store'])->name('admin.role.create.post');
    Route::put('admin/role/edit/{id}', [RoleController::class, 'store'])->name('admin.role.edit.post'); // Updated to PUT method
    Route::delete('admin-role-deletemulti', [RoleController::class, 'multidelete'])->name('admin.role.deletemulti');
    Route::get('admin/role/export', [RoleController::class, 'export'])->name('admin.role.export');
    Route::post('admin/role/import', [RoleController::class, 'import'])->name('admin.role.import');
    Route::get('/sample-file-download-role', [RoleController::class, 'sampleFileDownloadRole'])->name('sample-file-download-role');
    Route::get('admin/role/access/{roleId}', [RoleController::class, 'manageAccess'])->name('admin.role.access');
    Route::post('admin/role/access/{roleId}', [RoleController::class, 'updateAccess'])->name('admin.role.updateAccess');


    // role permissions

    Route::get('admin/role-permissions/{id}', [RolePermissionController::class, 'index'])->name('admin.rolepermission');
    Route::post('admin/role-permissions-update', [RoleController::class, 'rolepermissionsupdate'])->name('admin.role-permissions-update');

    Route::post('admin/role-permision-store', [RolePermissionController::class, 'store'])->name('admin.rolepermission.save');
    Route::get('admin/role/create', [RoleController::class, 'create'])->name('admin.role.create');
    Route::get('admin/role/edit/{id}', [RoleController::class, 'edit'])->name('admin.role.edit');
    Route::get('admin/role/delete/{id}', [RoleController::class, 'remove'])->name('admin.role.delete');
    Route::post('admin/role/create', [RoleController::class, 'store'])->name('admin.role.create.post');
    Route::put('admin/role/edit/{id}', [RoleController::class, 'store'])->name('admin.role.edit.post'); // Updated to PUT method
    


    // -----------------------------------Permission Routes--------------------------------------
    Route::get('admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions');
    Route::post('admin-permision-save', [PermissionController::class, 'store'])->name('admin.permission.save');
    Route::get('admin-permision-detele/{id}', [PermissionController::class, 'destroy'])->name('admin.permission.destroy');
    Route::delete('role-delete-selected', [PermissionController::class, 'multidelete'])->name('admin.permission.deleteSelected');

    Route::get('getPermissionData', [PermissionController::class, 'getPermissionData'])->name('getPermissionData');

    Route::post('create/feature', [PermissionController::class, 'createFeature'])->name('admin.feature.save');
    Route::post('create/module', [PermissionController::class, 'createModule'])->name('admin.module.save');

    // main admin
    Route::get('admin/main_users', [MainUserController::class, 'index'])->name('admin.mainUsers');
    Route::post('admin/main_users-save', [MainUserController::class, 'store'])->name('admin.mainUsers.save');
    Route::post('admin/main_users-delete/{id}', [MainUserController::class, 'remove'])->name('admin.mainUsers.delete');

    // users
    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users');    
    Route::get('get-user', [UserController::class, 'getUser'])->name('admin.user.edit');
    Route::post('admin/users-save', [UserController::class, 'store'])->name('admin.users.save');
    Route::post('admin/users-delete/{id}', [UserController::class, 'remove'])->name('admin.users.delete');
    


    // party
    Route::get('admin/party', [PartyController::class, 'index'])->name('admin.party');
    Route::post('admin/party-save', [PartyController::class, 'store'])->name('admin.party.save');
    Route::get('admin/party-delete/{id}', [PartyController::class, 'remove'])->name('admin.party.delete');
    Route::post('admin-party-delete-selected', [PartyController::class, 'remove'])->name('admin.party.deletemulti');
    Route::post('admin-party-update-status/{id}', [PartyController::class, 'updateStatus'])->name('admin.party.updatre-status');
    Route::get('admin-party-get-data', [PartyController::class, 'getData'])->name('admin.party.getData');


    // BOPP categories

    Route::get('admin/bopp-stock-pp-categories', [BoppCategoryController::class, 'index'])->name('bopp-stock.categories.view');    
    Route::post('add-category', [BoppCategoryController::class, 'save'])->name('admin.bopp-stock-pp-categories.save');
    Route::get('delete-category/{id}', [BoppCategoryController::class, 'remove'])->name('admin.bopp-stock-pp-categories.remove');
    Route::delete('boppstock-delete-selected-categories', [BoppCategoryController::class, 'multidelete'])->name('bopp-stock.categories.deletemulti');

    // BOPP Items
    Route::get('admin/bopp-stock-pp-item', [BoppItemController::class, 'index'])->name('boppstock.items.view');    
    Route::post('add-item', [BoppItemController::class, 'save'])->name('admin.bopp-stock-pp-item.save');
    Route::get('delete-item/{id}', [BoppItemController::class, 'remove'])->name('admin.bopp-stock-pp-item.remove');
    Route::delete('boppstock-delete-selected-items', [BoppCategoryController::class, 'multidelete'])->name('bopp-stock.items.deletemulti');

    // Non woven categorry
    Route::get('admin/non-woven-categories', [NonWovenCategoryController::class, 'index'])->name('non-wovenfabricstock.categories.view');    
    Route::post('add-non-woven-category', [NonWovenCategoryController::class, 'save'])->name('admin.NonWovenCategory.save');
    Route::get('delete-non-category/{id}', [NonWovenCategoryController::class, 'remove'])->name('admin.NonWovenCategory.remove');
    Route::delete('non-woven-delete-selected-categories', [NonWovenCategoryController::class, 'multidelete'])->name('non-wovenfabricstock.categories.deletemulti');

    // Non woven Items
    Route::get('admin/non-woven-item', [NonWovenItemController::class, 'index'])->name('non-wovenfabricstock.items.view');    
    Route::post('add-non-woven-item', [NonWovenItemController::class, 'save'])->name('admin.NonWovenItem.save');
    Route::get('delete-non-woven-item/{id}', [NonWovenItemController::class, 'remove'])->name('non-wovenfabricstock.items.delete');
    Route::delete('non-woven-delete-selected-items', [NonWovenItemController::class, 'multidelete'])->name('non-wovenfabricstock.items.deletemulti');

    // PP woven categorry
    Route::get('admin/pp-woven-categories', [PPWovenCategoryController::class, 'index'])->name('ppwovenfabricstock.categories.view');    
    Route::post('add-pp-woven-category', [PPWovenCategoryController::class, 'save'])->name('admin.PPWovenCategory.save');
    Route::get('delete-pp-category/{id}', [PPWovenCategoryController::class, 'remove'])->name('admin.PPWovenCategory.remove');
    Route::delete('pp-woven-delete-selected-categories', [PPWovenCategoryController::class, 'multidelete'])->name('pp-wovenfabricstock.categories.deletemulti');

    // PP woven Items
    Route::get('admin/pp-woven-item', [PPWovenItemController::class, 'index'])->name('ppwovenfabricstock.items.view');    
    Route::post('add-pp-woven-item', [PPWovenItemController::class, 'save'])->name('admin.PPWovenItem.save');
    Route::get('delete-pp-woven-item/{id}', [PPWovenItemController::class, 'remove'])->name('admin.PPWovenItem.remove');
    Route::post('pp-woven-delete-selected-items', [PPWovenItemController::class, 'multidelete'])->name('pp.woven.fabricstock.items.deletemulti');


    // Order books
    Route::get('admin/order-books', [OrderBookconteroller::class, 'index'])->name('orderbooks.items.view');    
    Route::post('add-order-books', [OrderBookconteroller::class, 'save'])->name('admin.orderbooks.save');

    Route::get('getOrderbookdata', [OrderBookconteroller::class, 'getOrderbookdata'])->name('getOrderbookdata');

    Route::get('delete-order-books/{id}', [OrderBookconteroller::class, 'remove'])->name('admin.orderbooks.remove');
    Route::delete('pp-woven-delete-selected-items', [OrderBookconteroller::class, 'multidelete'])->name('orderbooks.items.deletemulti');

    Route::get('admin/jobcode/get-jobcode-list/{val}', [OrderBookconteroller::class, 'getjobcodelist'])->name('get-jobcode-list');
    Route::get('admin/party/get-partyname-list/{val}', [OrderBookconteroller::class, 'getpartynamelist'])->name('get-partyname-list');


    Route::get('admin/jobcode/get-jobcode/{val}', [OrderBookconteroller::class, 'getjobcode'])->name('get-jobcode');


    // Job types
    Route::get('admin/jobtypes', [JobTypesController::class, 'index'])->name('jobtypes.view');    
    Route::post('admin/jobtypes-save', [JobTypesController::class, 'save'])->name('jobtypes.save');    
    Route::get('admin/jobtypes-delete/{id}', [JobTypesController::class, 'remove'])->name('jobtypes.remove');    
    Route::post('jobtypes-delete-selected-items', [JobTypesController::class, 'multidelete'])->name('jobtypes.deletemulti');    

    // Job names
    // Route::get('admin/jobnames', [JobNamesController::class, 'index'])->name('jobnames.view');    
    // Route::post('admin/jobnames-save', [JobNamesController::class, 'save'])->name('jobnames.save');    
    // Route::get('admin/jobnames-delete/{id}', [JobNamesController::class, 'remove'])->name('jobnames.remove');    
    // Route::post('jobnames-delete-selected-items', [JobNamesController::class, 'multidelete'])->name('jobnames.deletemulti');    
    // Route::post('jobnames-update-status/{id}', [JobNamesController::class, 'updateStatus'])->name('jobnames.updatestatus');    
    

    // Job details
    Route::get('admin/job-details', [JobdetailsController::class, 'index'])->name('jobdetails.view');    

    Route::get('admin/job-details-all', [JobdetailsController::class, 'index_all'])->name('jobdetails.view.all');    
    Route::get('admin/job-details-pending', [JobdetailsController::class, 'index_pending'])->name('jobdetails.view.pending');    
    Route::get('admin/job-details-saved', [JobdetailsController::class, 'index_saved'])->name('jobdetails.view.saved');    
    // Route::get('admin/job-details-pending', [JobdetailsController::class, 'pending'])->name('jobdetails.pending.view');    
    // Route::get('admin/job-details-saved', [JobdetailsController::class, 'saved'])->name('jobdetails.saved.view');    
    Route::post('jobdetail-save', [JobdetailsController::class, 'store'])->name('jobdetails.save');    
    Route::get('getJobdetailsEdit', [JobdetailsController::class, 'getJobdetailsEdit'])->name('getJobdetailsEdit');
    Route::get('jobdetail-remove/{id}', [JobdetailsController::class, 'remove'])->name('jobdetails.remove');    
    Route::post('jobdetail-deletemulti', [JobdetailsController::class, 'multidelete'])->name('jobdetails.deletemulti');    

    Route::get('jobdetails-approve/{id}', [JobdetailsController::class, 'approveJob'])->name('approveJob');

    Route::get('check-bopp-item', [JobdetailsController::class, 'checkboppitem'])->name('check-bopp-item');
    Route::get('check-fibre-item', [JobdetailsController::class, 'checkfibreitem'])->name('check-fibre-item');
    Route::get('check-fibre-pp-item', [JobdetailsController::class, 'checkfibrePPitem'])->name('check-fibre-pp-item');
    Route::get('check-job-name', [JobdetailsController::class, 'checkjobName'])->name('checkjobName');

    Route::get('bopp_item_code_suggestions', [JobdetailsController::class, 'getBoppItems'])->name('bopp_item_code_suggestions');
    Route::get('fabric_item_code_suggestions', [JobdetailsController::class, 'getFabricItems'])->name('fabric_item_code_suggestions');
    Route::get('fabric_item_code_pp_suggestions', [JobdetailsController::class, 'getFabricPPItems'])->name('fabric_item_code_pp_suggestions');
    
    Route::get('getPartyDetails', [JobdetailsController::class, 'getParyDetails'])->name('getPartyDetails');
    Route::get('getJobDetails', [JobdetailsController::class, 'getJobDetails'])->name('getJobDetails');

    Route::post('remove-image', [JobdetailsController::class, 'removeImage'])->name('jobdetails.remove-image');
    


    //----------------------- Stocks ------------------------

    // Bopp

    Route::get('material-stock-bopp', [StocksBoppController::class, 'index'])->name('admin.material-stock.bopp');
    Route::get('material-stock-bopp-roll', [StocksBoppController::class, 'indexroll'])->name('admin.material-stock.bopp-roll');
    Route::get('material-stock-bopp-list', [StocksBoppController::class, 'getBoppData'])->name('admin.material-stock.bopp-list');
    Route::get('material-stock-bopproll-list', [StocksBoppController::class, 'getBoppRollData'])->name('admin.material-stock.bopproll-list');

    Route::post('material-stock-bopp-save', [StocksBoppController::class, 'save'])->name('material-stock.bopp-save');
    Route::post('material-stock-boppissue-save', [StocksBoppController::class, 'saveIssue'])->name('material-stock.boppissue-save');
    Route::get('getBoppRoll', [StocksBoppController::class, 'getBoppRoll'])->name('getBoppRoll');
    Route::get('check_issue_bopp', [StocksBoppController::class, 'check_issue_bopp'])->name('check_issue_bopp');

    Route::get('material-stokk-bopp-received', [StocksBoppController::class, 'index_received'])->name('admin.material-stock.bopp-received');
    Route::get('material-stokk-bopp-issued', [StocksBoppController::class, 'index_issued'])->name('admin.material-stock.bopp-issued');


    Route::get('update-submodule', function (Request $request){
        $id = $request->id;
        $submodules = \App\Models\Module::where('parent_id', $id)->get();

        if ($submodules) {
            return response()->json([
                'success' => true,
                'data' => $submodules
            ]);
        }
    })->name('update-submodule');



    // Master Ajax
    Route::post('add-party-master', [MasterAjaxController::class, 'saveParty'])->name('addpartymaster');

    Route::get('check-bopp', [MasterAjaxController::class, 'checkbopp'])->name('check-bopp');
    Route::post('add-boppItem-master', [MasterAjaxController::class, 'saveboppItem'])->name('addboppItemmaster');

    Route::get('check-fabric', [MasterAjaxController::class, 'checkfabric'])->name('check-fabric');
    Route::post('add-fabricItem-master', [MasterAjaxController::class, 'savefabricItem'])->name('addfabricItemmaster');

    Route::get('check-fabric-pp', [MasterAjaxController::class, 'checkfabricpp'])->name('check-fabric-pp');
    Route::post('add-fabricppItem-master', [MasterAjaxController::class, 'savefabricppItem'])->name('addfabricppItemmaster');
    
});







