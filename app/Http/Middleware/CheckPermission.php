<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Role;
use App\Models\Menu;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$permission_keys)
    {
        $continueApp = false;

        $auth = Auth::user();

        if ($auth->roles->count() > 0) {
            $role = Role::findOrFail($auth->roles[0]->id);

            foreach ($permission_keys as $index => $key_name) {
                if ($index !== 0) {
                    $menu = Menu::where('key_name', $key_name)->firstOrFail();
        
                    $countPermission = $role->menus()->wherePivot('menu_id', $menu->id)->wherePivot('permission', $permission_keys[0])->count();
        
                    if ($countPermission > 0) {
                        $continueApp = true;
                    }
                }
            }
        }

        if ($continueApp) {
            return $next($request);
        } else {
            $data['errors'] = 'Do not have access rights on this permission';

            return response()->json($data, 403);
        }
    }
}
