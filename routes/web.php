<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\RolesController;
use App\Http\Controllers\CargosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\ModulosController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\ProcedimientosController;

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

Route::get('admin/login', function () {
    return view('Admin.Sistema.Acceso.login');
})->name('admin.login');

Route::post('admin/authin', [UsuariosController::class, 'authenticate'])->name('admin.auth');


Route::prefix('admin')->middleware('auth')->group(function () {

    /* *** sistema *** */
    Route::get('home', [UsuariosController::class, 'home'])->name('admin.home');

    Route::get('logauth', [UsuariosController::class, 'logauth'])->name('auth.logauth');


    /* *** Catálogos *** */

        /* *** roles *** */
        Route::prefix('roles')->group(function (){

            Route::get('/', [RolesController::class, 'index'] )->name('roles');
            Route::get('all', [RolesController::class, 'listar'])->name('roles.listar');
            Route::get('get',[RolesController::class, 'obtener'])->name('roles.obtener');
            Route::post('save', [RolesController::class, 'save'])->name('roles.save');
            Route::post('del', [RolesController::class, 'delete'])->name('roles.del');
            Route::get('select', [RolesController::class, 'ListarRolesSelect'])->name('roles.select.listar');
    
        });

         /* *** roles *** */
         Route::prefix('cargos')->group(function (){
            Route::get('/', [CargosController::class, 'index'] )->name('cargos');
            Route::get('all', [CargosController::class, 'listar'])->name('cargos.listar');
            Route::get('get',[CargosController::class, 'obtener'])->name('cargos.obtener');
            Route::post('save', [CargosController::class, 'save'])->name('cargos.save');
            Route::post('del', [CargosController::class, 'desactivar'])->name('cargos.del');
            Route::get('select', [CargosController::class, 'ListarCargosSelect'])->name('cargos.select.listar');
    
        });


        /* *** usuarios *** */
        Route::prefix('usuarios')->group(function (){
            Route::get('/', [UsuariosController::class, 'gestionUsuariosSistema'] )->name('usuarios');
            Route::get('all', [UsuariosController::class, 'listar'])->name('usuarios.listar');
            Route::get('get',[UsuariosController::class, 'obtener'])->name('usuarios.obtener');
            Route::post('save', [UsuariosController::class, 'save'])->name('usuarios.save');
            Route::post('del', [UsuariosController::class, 'desactivar'])->name('usuarios.del');
        });

      
        /* *** modulos *** */
        Route::prefix('modulos')->group(function (){

            Route::get('/', [ModulosController::class, 'index'] )->name('modulos');
            Route::get('all', [ModulosController::class, 'listar'])->name('modulos.listar');
            Route::get('get',[ModulosController::class, 'obtener'])->name('modulos.obtener');
            Route::post('save', [ModulosController::class, 'save'])->name('modulos.save');
            Route::post('del', [ModulosController::class, 'delete'])->name('modulos.del');
            Route::get('select', [ModulosController::class, 'ListarModulosSelect'])->name('modulos.select.listar');
        });


        /* *** permisos *** */

        Route::prefix('permisos')->group(function (){

            Route::get('/', [PermisosController::class, 'index'] )->name('permisos');
            Route::get('all', [PermisosController::class, 'listar'])->name('permisos.listar');
            Route::get('get',[PermisosController::class, 'obtener'])->name('permisos.obtener');
            Route::post('save', [PermisosController::class, 'save'])->name('permisos.save');
            Route::post('del', [PermisosController::class, 'delete'])->name('permisos.del');
            Route::get('listar/rol', [PermisosController::class, 'ListarPermisosRol'])->name('permisos.listar.rol');
        });


        /* *** procedimientos *** */
        Route::prefix('procedimientos')->group(function (){
            Route::get('/', [ProcedimientosController::class, 'index'] )->name('procedimientos');
            Route::get('all', [ProcedimientosController::class, 'listar'])->name('procedimientos.listar');
            Route::get('get',[ProcedimientosController::class, 'obtener'])->name('procedimientos.obtener');
            Route::post('save', [ProcedimientosController::class, 'save'])->name('procedimientos.save');
            Route::post('del', [ProcedimientosController::class, 'delete'])->name('procedimientos.del');
            Route::get('recurso/download', [ProcedimientosController::class, 'DescargarArchivos'])->name('procedimientos.download');
        });
   






});

    


/* ** Delivery*** */
