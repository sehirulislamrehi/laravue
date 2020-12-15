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
    Route::get('/roles/{item}', [RoleController::class, 'show'])->name('roles.search');
    Route::post('/roles/add', [RoleController::class, 'store'])->name('add');
    Route::post('/roles/update/{id}', [RoleController::class, 'update'])->name('update');
    Route::post('/roles/delete/{id}', [RoleController::class, 'destroy'])->name('delete');
    Route::post('/roles/delete_all/',[RoleController::class,'delete_all'])->name('delete.all');
    Route::get('/verify',[UserController::class,'verify']);

<<<<<<< HEAD
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/add', [UserController::class, 'store'])->name('user.add');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('/users/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
=======
    //user route start
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/add', [UserController::class, 'store'])->name('users.add');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.delete');
    Route::post('/users/delete_all/',[UserController::class,'delete_all'])->name('users.delete.all');
>>>>>>> 25e82745305c70409f467a0bae9a65467d87dbc9

    Route::get('/cruds/all',[CrudController::class,'all'])->name('all');
    Route::post('/cruds/add',[CrudController::class,'store'])->name('add');
    Route::post('/cruds/update/{id}',[CrudController::class,'update'])->name('update');
    Route::post('/cruds/delete/{id}',[CrudController::class,'delete'])->name('delete');
});
