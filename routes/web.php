<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('admin/home', function () {
    return view('Admin.layout');
})->name('admin.home');

Route::get('admin/login', function () {
    return view('Admin.Sistema.Usuarios.login');
})->name('admin.login');
