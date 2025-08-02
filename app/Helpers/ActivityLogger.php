<?php 
namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($module, $action, $comments = null, $user_id = null)
    {
        Log::create([
            'user_id' => $user_id ?? Auth::id(),
            'module' => $module,
            'action' => $action,
            'comments' => $comments,
            'action_date' => now(),
        ]);
    }
}
