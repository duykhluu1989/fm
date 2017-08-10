<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;

class Permission
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        $route = $request->route();

        $isAdmin = false;
        $permissions = array();

        foreach($user->userRoles as $userRole)
        {
            if($userRole->role->name == Role::ROLE_ADMINISTRATOR)
            {
                $isAdmin = true;
                break;
            }

            if(!empty($userRole->role->permission))
                $permissions = array_merge(json_decode($userRole->role->permission, true));
        }

        if($isAdmin == false && in_array('permission', $route->middleware()))
        {
            if(count($permissions) == 0)
                return response()->view('backend.errors.403');

            if(!in_array($route->methods()[0] . ' - ' . $route->uri(), $permissions))
                return response()->view('backend.errors.403');
        }

        return $next($request);
    }
}
