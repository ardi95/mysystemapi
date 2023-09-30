<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Role;
use App\Models\Menu;

class CheckMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$keys)
    {
        $continueApp = false;

        $auth = Auth::user();

        if ($auth->roles->count() > 0) {
            $role = Role::findOrFail($auth->roles[0]->id);

            foreach ($keys as $key_name) {
                $menu = Menu::where('key_name', $key_name)->firstOrFail();
    
                $countAccess = $role->menus()->wherePivot('menu_id', $menu->id)->wherePivot('permission', 'access')->count();
    
                if ($countAccess > 0) {
                    $continueApp = true;
                }
            }
        }

        if ($continueApp) {
            return $next($request);
        } else {
            $data['errors'] = 'Do not have access rights on this menu';

            return response()->json($data, 403);
        }
    }
}
