<?php

namespace App\Http\Controllers\AppManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Role;
use App\Models\Menu;

use Validator;

class RoleController extends Controller
{
    public function __construct() {
        $this->middleware('permission:create,role', ['only' => ['store']]);
        $this->middleware('permission:update,role', ['only' => ['update']]);
        $this->middleware('permission:delete,role', ['only' => ['destroy']]);
    }
    
    public function temp() {
        $role = Role::get();

        return response()->json($role, 200);
    }

    public function index(Request $request) {
        $data = Role::select('*');

        if ((trim($request->order_field) != '' or trim($request->order_field) != null) and (trim($request->order_dir) != '' or trim($request->order_dir) != null)) {
            $data = $data->orderBy($request->order_field, $request->order_dir);
        }

        if (trim($request->search) != '' or trim($request->search) != null) {
            $data = $data->where('name', 'like', '%' . $request->search . '%');
        }

        $data = $data->orderBy('id', 'desc')->paginate($request->per_page);

        return response()->json($data, 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,NULL,id,deleted_at,NULL',
        ], [
            'name.unique' => 'Key name can not be same.'
        ]);

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $value) {
                $data['errors'][$a] = $value[0];
                $a++;
            }

            $error = 1;
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request) {
                Role::create([
                    'name' => $request->name,
                ]);

                $data['status'] = 'success';
                $data['message'] = 'Success add data role';

                return $data;
            }), 200);
        }
    }

    public function update(Request $request, $id) {
        $detail = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,'.$id.',id,deleted_at,NULL'
        ], [
            'key_name.unique' => 'Key name can not be same.'
        ]);

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach ($errors as $value) {
                $data['errors'][$a] = $value[0];
                $a++;
            }

            $error = 1;
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request, $detail) {
                $detail->update([
                    'name' => $request->name
                ]);

                $data['status'] = 'success';
                $data['message'] = 'Success edit data role';

                return $data;
            }), 200);
        }
    }

    public function destroy($id)
    {
        $detail = Role::findOrFail($id);

        return response()->json(DB::transaction(function () use ($detail) {
            $detail->users()->detach();
            $detail->menus()->detach();
            $detail->delete();

            $data['status'] = 'success';
            $data['message'] = 'Success delete data role';

            return $data;
        }), 200);

    }

    // public function nestedAddRoleMenu($parentId, $role) {
    //     $menu = Menu::findOrFail($parentId);
    //     $role->menus()->attach($menu->id);

    //     if ($menu->parent_menu_id !== NULL) {
    //         $menuParent = Menu::findOrFail($menu->parent_menu_id);
    //         $attachedMenus = $role->menus->pluck('id');
            
    //         if (!$attachedMenus->contains($menuParent->id)) {
    //             $this->nestedAddRoleMenu($menu->parent_menu_id, $role);
    //         }
    //     }
    // }
}

