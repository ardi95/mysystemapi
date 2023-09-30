<?php

namespace App\Http\Controllers\AppManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use App\Models\Menu;
use App\Models\Role;

use Validator;

class RoleMenuController extends Controller
{
    public function __construct() {
        $this->middleware('permission:update,rolemenu', ['only' => ['update']]);
    }

    public function nested_menu($parentId) {
        $submenus = Menu::where('parent_menu_id', $parentId)->orderBy('order_number', 'asc')->get();

        // dd($submenus);

        foreach ($submenus as $submenu) {
            $submenu->submenus = $this->nested_menu($submenu->id);
        }

        return $submenus;
    }

    public function allStructure() {
        // MENU
        $menuItems = Menu::whereNull('parent_menu_id')->orderBy('order_number', 'asc')->get();

        foreach ($menuItems as $menuItem) {
            $menuItem['submenus'] = $this->nested_menu($menuItem->id);
        }

        $data = $menuItems;
        // MENU

        return response()->json($data, 200);
    }

    public function menu_role_list($id) {
        $role = Role::findOrFail($id);

        $data = $role->menus;

        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        // dd($request->datamenu);

        return response()->json(DB::transaction(function () use ($request, $id) {
            $role = Role::findOrFail($id);
            $role->menus()->detach();

            if ($request->datamenu !== NULL) {
                foreach ($request->datamenu as $datamenu) {
    
                    $role->menus()->attach($datamenu['menu_id'], ['permission' => $datamenu['permission']]);
                }
            }

            $data['status'] = 'success';
            $data['message'] = 'Success update data role menu';

            return $data;
        }), 200);
    }
}
