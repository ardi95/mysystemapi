<?php

namespace App\Http\Controllers\AppManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Menu;

use Validator;

class MenuController extends Controller
{
    public function __construct() {
        $this->middleware('permission:create,menu', ['only' => ['store']]);
        $this->middleware('permission:update,menu', ['only' => ['update']]);
        $this->middleware('permission:delete,menu', ['only' => ['destroy']]);
    }

    public function index(Request $request) {
        $data = Menu::with(['child_menus', 'parent_menu']);

        if ((trim($request->order_field) != '' or trim($request->order_field) != null) and (trim($request->order_dir) != '' or trim($request->order_dir) != null)) {
            $data = $data->orderBy($request->order_field, $request->order_dir);
        }

        if (trim($request->search) != '' or trim($request->search) != null) {
            $data = $data->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('url', 'like', '%' . $request->search . '%')
                ->orWhere('key_name', 'like', '%' . $request->search . '%');
        }

        $data = $data->orderBy('id', 'desc')->paginate($request->per_page);

        return response()->json($data, 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'key_name' => 'required|unique:menus,key_name,NULL,id,deleted_at,NULL',
            'name' => 'required',
            'order_number' => 'required|numeric|min:1',
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

        if (preg_match('/\s/', $request->key_name)) {
            $data['errors'][$a] = 'Key Name cannot contain spaces';
            $a++;
            $error = 1;
        }

        if (!preg_match('/^[a-z]+$/', $request->key_name)) {
            $data['errors'][$a] = 'Key Name must be lowercase';
            $a++;
            $error = 1;
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request) {
                Menu::create([
                    'key_name' => $request->key_name,
                    'name' => $request->name,
                    'url' => $request->url,
                    'order_number' => $request->order_number,
                    'parent_menu_id' => $request->parent_menu_id
                ]);

                $data['status'] = 'success';
                $data['message'] = 'Success add data menu';

                return $data;
            }), 200);
        }
    }

    public function update(Request $request, $id) {
        $detail = Menu::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'key_name' => 'required|unique:menus,key_name,'.$id.',id,deleted_at,NULL',
            'name' => 'required',
            'order_number' => 'required|numeric|min:1',
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

        if (preg_match('/\s/', $request->key_name)) {
            $data['errors'][$a] = 'Key Name cannot contain spaces';
            $a++;
            $error = 1;
        }

        if (!preg_match('/^[a-z]+$/', $request->key_name)) {
            $data['errors'][$a] = 'Key Name must be lowercase';
            $a++;
            $error = 1;
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request, $detail) {
                $detail->update([
                    'key_name' => $request->key_name,
                    'name' => $request->name,
                    'url' => $request->url,
                    'order_number' => $request->order_number,
                    'parent_menu_id' => $request->parent_menu_id
                ]);

                $data['status'] = 'success';
                $data['message'] = 'Success edit data menu';

                return $data;
            }), 200);
        }
    }

    public function destroy($id)
    {
        $detail = Menu::findOrFail($id);

        $count = Menu::where('parent_menu_id', $id)->count();

        $error = 0;
        $a = 0;
        $data = array();
        $data['errors'] = [];

        if ($count > 0) {
            $data['errors'][$a] = 'Cannot delete this menu because there are still menu branches in this menu';
            $a++;
            $error = 1;
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($detail) {
                $detail->roles()->detach();
                $detail->delete();
    
                $data['status'] = 'success';
                $data['message'] = 'Success delete data menu';
    
                return $data;
            }), 200);
        }

    }
}
