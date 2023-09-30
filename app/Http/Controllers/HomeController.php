<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Menu;
use App\Models\Role;

class HomeController extends Controller
{
    public function index() {
        $auth = Auth::user();

        // CARD USER
        $status_user = false;
        $count_user = 0;

        // CARD MENU
        $status_menu = false;
        $count_menu = 0;

        // CARD ROLE
        $status_role = false;
        $count_role = 0;

        if ($auth->roles->count() > 0) {
            $role = Role::findOrFail($auth->roles[0]->id);

            if ($role->name === 'Admin APP') {
                // CARD USER
                $menuUsers = $role->menus()->wherePivot('permission', 'access')->where('key_name', 'users');
    
                // CARD MENU
                $menuMenu = $role->menus()->wherePivot('permission', 'access')->where('key_name', 'menu');
    
                // CARD ROLE
                $menuRole = $role->menus()->wherePivot('permission', 'access')->where('key_name', 'role');
    
                // CARD USER
                if ($menuUsers->count() > 0) {
                    $status_user = true;
                    $count_user = User::count();
                }
    
                // CARD MENU
                if ($menuMenu->count() > 0) {
                    $status_menu = true;
                    $count_menu = Menu::count();
                }
    
                // CARD ROLE
                if ($menuRole->count() > 0) {
                    $status_role = true;
                    $count_role = Role::count();
                }
            }
        }

        $data = [
            'status_user' => $status_user,
            'count_user' => $count_user,
            'status_menu' => $status_menu,
            'count_menu' => $count_menu,
            'status_role' => $status_role,
            'count_role' => $count_role,
        ];

        return response()->json($data, 200);
    }
}
