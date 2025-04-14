<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\LoginController;
use App\Models\data;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckSession;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');


Route::get('/home', [DataController::class, 'main'])->name('main')->middleware('checkSession');
Route::get('/tree', [DataController::class, 'treeLayout'])->name('tree');

Route::get('/list', [DataController::class, 'listofdata'])->name('list');
    Route::get('list/tree-list/view/{id}', [DataController::class, 'view'])->name('view');
    Route::get('/member-tree', [DataController::class, 'allmemberTree'])->name('displaytree');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('list/add/{id}', [DataController::class, 'dropdownrecord'])->name('listadd');

Route::post('/childstore', [DataController::class, 'childstore'])->name('childstore');
Route::get('/get-children/{id}', [DataController::class, 'getChildren']);
Route::get('/get-newfamily-children/{id}', [DataController::class, 'getnewfamilyChildren']);


Route::get('/get-children-by-parent', [DataController::class, 'getChildrenByParent'])->name('getChildrenByParent');

Route::get('/childform', [DataController::class, 'childform'])->name('childform');
Route::get('/list/form', [DataController::class, 'dataform'])->name('form');
Route::post('/store', [DataController::class, 'store'])->name('store');

Route::get('/delete/{id}', [DataController::class, 'deleteParent'])->name('delete');
Route::get('/child-delete/{id}', [DataController::class, 'childdelete'])->name('child-delete');


Route::get('list/edit/{id}', [DataController::class, 'edit'])->name('edit');
Route::put('/update/{id}', [DataController::class, 'update'])->name('update');

Route::get('list/child-edit/{id}', [DataController::class, 'childedit'])->name('childedit');
Route::put('/child-update/{id}', [DataController::class, 'childupdate'])->name('childupdate');


Route::get('/child-check/{id}', [DataController::class, 'checkChild']);
Route::get('/tree2', function(){
    return view('tree2');
});



Route::post('/check-email', [DataController::class, 'checkEmail'])->name('check.unique.email');
Route::post('/check-phone', [DataController::class, 'checkPhone'])->name('check.unique.phone');


