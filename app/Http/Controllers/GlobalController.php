<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class GlobalController extends Controller
{
    public function checkRole($id) {
        $result = false;

        $countRole = User::where('id', Auth::user()->id)->whereHas('roles', function ($query) use ($id) {
            $query->where('roles.id', $id);
        })->count();

        if ($countRole > 0) {
            $result = true;
        }

        return $result;
    }
}
