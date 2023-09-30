<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Role;
use App\Models\Menu;

use Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' 	=> 'required|email',
            'password' 	=> 'required|min:6'
        ]);

        $a = 0;
        $data = array();
        $data['errors'] = [];
        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            foreach ($errors as $value) {
                $data['errors'][$a] = $value[0];
                $a++;
            }
            return response()->json($data, 400);
        } else {
            $user = User::where('email', '=', $request->email);

            if ($user->count() > 0) {
                $user_find = $user->first();

                if ($user_find->active === 'Yes') {
                    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                        $user = Auth::user();
                        $success['token'] =  $user->createToken('MyApp')->accessToken;
                        return response()->json($success, 200);
                    }
                    else{
                        return response()->json(['errors'=>['Password Invalid']], 400);
                    }
                } else {
                    return response()->json(['errors'=>['User not Active']], 400);
                }
            } else {
                return response()->json(['errors'=>['User not found']], 400);
            }
        }
    }

    public function nested_menu($parentId) {
        $submenus = Menu::where('parent_menu_id', $parentId)->orderBy('order_number', 'asc')->get();

        // dd($submenus);

        foreach ($submenus as $submenu) {
            $submenu->submenus = $this->nested_menu($submenu->id);
        }

        return $submenus;
    }

    public function nested_menu2($parentId, $role_id) {
        // $submenus = Menu::whereHas('roles', function($q) use ($role_id) {
        //     $q->where('id', $role_id);
        // })->where('parent_menu_id', $parentId)->orderBy('order_number', 'asc')->get();

        $role = Role::findOrFail($role_id);

        $submenus = $role->menus()->wherePivot('permission', 'access')->where('parent_menu_id', $parentId)->orderBy('order_number', 'asc')->get();

        // dd($submenus);

        foreach ($submenus as $submenu) {
            $submenu->submenus = $this->nested_menu($submenu->id);
        }

        return $submenus;
    }

    public function detail_user() {
        $data['user'] = Auth::user();
        $data['user']->url_photo = 'tes link';
        
        // MENU
        // $menuItems = Menu::whereNull('parent_menu_id')->orderBy('order_number', 'asc')->get();

        // foreach ($menuItems as $menuItem) {
        //     $menuItem['submenus'] = $this->nested_menu($menuItem->id);
        // }

        // $data['menu2'] = $menuItems;
        // MENU

        // MENU 2
        $user = Auth::user();

        $menuItems2 = [];

        if ($user->roles->count() > 0) {
            $menuItems2 = NULL;
            $role = Role::findOrFail($user->roles[0]->id);

            $menuItems2 = $role->menus()->wherePivot('permission', 'access')->whereNull('parent_menu_id')->orderBy('order_number', 'asc')->get();

            // $menuItems2 = Menu::whereHas('roles', function($q) use ($user) {
            //     $q->where('id', $user->roles[0]->id);
            // })->whereNull('parent_menu_id')->orderBy('order_number', 'asc')->get();
    
            foreach ($menuItems2 as $menuItem2) {
                $menuItem2['submenus'] = $this->nested_menu2($menuItem2->id, $user->roles[0]->id);
            }
    
        }
        $data['menu'] = $menuItems2;
        // MENU 2

        return response()->json($data, 200);
    }

    public function token_notfound()
    {
        return response()->json(['message' => 'token not found'], 403);
    }

    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json(['message' => 'Logout success'], 200);
    }

    public function permissionByMenu(Request $request) {
        $data['access'] = false;
        $data['create'] = false;
        $data['update'] = false;
        $data['delete'] = false;

        $auth = Auth::user();
        
        if ($auth->roles->count() > 0) {
            $role = Role::findOrFail($auth->roles[0]->id);
            $menu = Menu::where('key_name', $request->key)->firstOrFail();

            $countAccess = $role->menus()->wherePivot('menu_id', $menu->id)->wherePivot('permission', 'access')->count();
            $countCreate = $role->menus()->wherePivot('menu_id', $menu->id)->wherePivot('permission', 'create')->count();
            $countUpdate = $role->menus()->wherePivot('menu_id', $menu->id)->wherePivot('permission', 'update')->count();
            $countDelete = $role->menus()->wherePivot('menu_id', $menu->id)->wherePivot('permission', 'delete')->count();

            if ($countAccess > 0) {
                $data['access'] = true;
            }

            if ($countCreate > 0) {
                $data['create'] = true;
            }

            if ($countUpdate > 0) {
                $data['update'] = true;
            }

            if ($countDelete > 0) {
                $data['delete'] = true;
            }
        }

        return response()->json($data, 200);
    }

    public function update(Request $request)
    {
        $auth = Auth::user();
        $user = User::findOrFail($auth->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'photo' => 'mimes:jpeg,jpg,png|max:5120',
            'gender' => 'required',
            'division' => 'required',
            'birthdate' => 'required|date_format:Y-m-d'
        ], [
            'photo.mimes' => 'Photo must have extension png.',
            'photo.max' => 'Photo must not be greater than 5120 kilobytes.',
            'gender' => 'required',
            'division' => 'required',
            'birthdate' => 'required|date_format:Y-m-d'
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

        //echo $request->file('photo')->getClientOriginalExtension();
        //die();

        if ($request->file('photo') !== NULL) {
            if ($request->file('photo')->getClientOriginalExtension() !== 'png') {
                $data['errors'][$a] = 'Photo must have extension png.';
                $a++;
                $error = 1;
            }
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request, $user, $auth) {
                // PHOTO
                if ($request->status_photo == 1) {
                    if ($user->photo !== NULL) {
                        try {
                            Storage::delete(env('DIR_PROFILE') . '/' . $user->photo);
                        } catch (FileNotFoundException $e) {
                            // File sudah dihapus/tidak ada
                        }
                    }
                    // PHOTO
                    $resultNameFile1 = NULL;

                    if ($request->file('photo') !== NULL) {
                        $uploadFile = $request->file('photo');
                        $nameFile = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $extensionFile = $uploadFile->getClientOriginalExtension();
                        $resultNameFile1 = $nameFile . "." . $extensionFile;
                        $nameFile2 = $nameFile;

                        $i = 2;
                        while (Storage::disk('local')->exists(env('DIR_PROFILE') . '/' . $nameFile . "." . $extensionFile)) {
                            $nameFile = (string) $nameFile2 . $i;
                            $resultNameFile1 = $nameFile . "." . $extensionFile;
                            $i++;
                        }

                        Storage::putFileAs(env('DIR_PROFILE'), $request->file('photo'), $resultNameFile1);
                    }
                } else {
                    $resultNameFile1 = $user->photo;
                }
    
                // echo $request->status;die();

                $user->name = $request->name;
                $user->photo = $resultNameFile1;
                $user->gender = $request->gender;
                $user->division = $request->division;
                $user->birthdate = $request->birthdate;
                $user->save();
                
                $data['data'] = $user;
                $data['status'] = 'success';
                $data['message'] = 'Success edit data profile';
    
                return $data;
            }), 200);
        }
    }

    public function changePassword(Request $request) {
        $auth = Auth::user();
        $user = User::findOrFail($auth->id);

        $validator = Validator::make($request->all(), [
            'password_before' => 'required|min:6|max:20',
            'password' => 'required|min:6|max:20',
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

        if (!(Hash::check($request->password_before, $user->password))) {
            // Password yang dimasukkan oleh pengguna cocok dengan yang ada di database
            // Tambahkan logika di sini jika verifikasi berhasil
            $data['errors'][$a] = 'Enter the previous password correctly';
            $a++;
            $error = 1;
        }

        if ($error == 1) {
            $data['status'] = 'error';
            return response()->json($data, 400);
        } else {
            return response()->json(DB::transaction(function () use ($request, $user) {
                $user->password = bcrypt($request->password);
                $user->save();

                $data['data'] = $user;
                $data['status'] = 'success';
                $data['message'] = 'Success change password';
    
                return $data;
            }), 200);
        }
    }
}
