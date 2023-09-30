<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppManagement\UserController;
use App\Http\Controllers\AppManagement\MenuController;
use App\Http\Controllers\AppManagement\RoleController;
use App\Http\Controllers\AppManagement\RoleMenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/login',[AuthController::class, 'login']);

Route::get('/token-notfound',[AuthController::class, 'token_notfound'])->name('token-notfound');


Route::middleware(['auth:api'])->group(function () {
    Route::get('/detail-user',[AuthController::class, 'detail_user']);
    Route::patch('/detail-user',[AuthController::class, 'update']);
    Route::patch('/detail-user/change-password',[AuthController::class, 'changePassword']);
    Route::get('/permission-by-menu',[AuthController::class, 'permissionByMenu']);

    Route::post('/logout',[AuthController::class, 'logout']);
    
    //HOME
    Route::get('/home', [HomeController::class, 'index']);
    
    Route::prefix('app-management')->group(function () {
        Route::get('/user/get-photo/{id}', [UserController::class, 'getPhoto']);
        
        Route::middleware(['menu:users'])->group(function () {
            Route::resource('user', UserController::class)->except(['show']);
            Route::get('/user/role', [RoleController::class, 'index']);
        });

        Route::prefix('menu')->middleware(['menu:menu'])->group(function () {
            Route::post('/', [MenuController::class, 'store']);
            Route::put('/{id}', [MenuController::class, 'update']);
            Route::delete('/{id}', [MenuController::class, 'destroy']);
        });
        
        Route::prefix('role')->middleware(['menu:role'])->group(function () {
            Route::post('/', [RoleController::class, 'store']);
            Route::put('/{id}', [RoleController::class, 'update']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
        });

        Route::prefix('role-menu')->middleware(['menu:rolemenu'])->group(function () {
            Route::get('/all-structure',  [RoleMenuController::class, 'allStructure']);
            Route::get('/menu-role-list/{id}',  [RoleMenuController::class, 'menu_role_list']);
            Route::patch('/{id}', [RoleMenuController::class, 'update']);
        });

        Route::middleware(['menu:rolemenu,menu,role'])->group(function () {
            Route::get('/all-structure',  [RoleMenuController::class, 'allStructure']);
            Route::get('/menu', [MenuController::class, 'index']);
            Route::get('/role', [RoleController::class, 'index']);
        });

    });
});
