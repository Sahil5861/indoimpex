<?php
// app/Helpers/helpers.php
if (!function_exists('str_limit_words')) {
    function str_limit_words($string, $words = 100, $end = '...')
    {
        $wordArr = explode(' ', $string);
        if (count($wordArr) <= $words) {
            return $string;
        }
        return implode(' ', array_slice($wordArr, 0, $words)) . $end;
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format a given date to 'd F Y' format.
     *
     * @param  string|\DateTime $date
     * @return string
     */
    function formatDate($date)
    {
        return \Carbon\Carbon::parse($date)->format('d F Y');
    }
}


if (!function_exists('hasPermission')) {
    function hasPermission($permissionName, $feature)
    {
        if (!Auth::check()) return false;

        

        $slug = DB::table('permissions')->where('name', $permissionName)->where('feature', $feature)->first();

        $slugName = '-';
        if ($slug) {
            $slugName = $slug->slug;
        }
        
        // echo $routeName;
        // exit;


        static $allowedSlugs;

        if (is_null($allowedSlugs)) {
            $user = Auth::user();
            $permission_ids = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->pluck('permission_id');

            $allowedSlugs = DB::table('permissions')
                ->whereIn('id', $permission_ids)
                ->pluck('slug')
                ->toArray();
        }

        return in_array($slugName, $allowedSlugs);
    }
}

if (!function_exists('hasAnyPermission')) {
    function hasAnyPermission($permissionName): bool
    {
        if (!Auth::check()) return false;

        static $allowedRoutes;

        $parentPermissionId = DB::table('permissions')->where('name', $permissionName)->first()->id;
        
        
        $routeNames = DB::table('permissions')->where('parent_id', $parentPermissionId)->get()->pluck('route')->toArray();        
        // print_r($routeNames); exit;

        if (is_null($allowedRoutes)) {
            $user = Auth::user();
            $permission_ids = DB::table('role_permissions')
                ->where('role_id', $user->role_id)
                ->pluck('permission_id');

            $allowedRoutes = DB::table('permissions')
                ->whereIn('id', $permission_ids)
                ->pluck('route')
                ->toArray();
        }

        // Check if any route in the list exists in user's allowed routes
        return count(array_intersect($routeNames, $allowedRoutes)) > 0;
    }
}

