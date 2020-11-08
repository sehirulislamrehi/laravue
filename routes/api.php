<?php

use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CrudController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



//login route
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');


Route::group(['middleware'=> ['auth:api'] ], function () {

    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/roles/add', [RoleController::class, 'store'])->name('add');
    Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('update');
    Route::post('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('delete');
    Route::get('/verify',[UserController::class,'verify']);

    Route::get('/cruds/all',[CrudController::class,'all'])->name('all');
    Route::post('/cruds/add',[CrudController::class,'store'])->name('add');
});
