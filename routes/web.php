<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController; // Adiciona o TicketController
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UrgencyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\PostoController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\TaskController;

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


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});


// Rotas de autenticação do Laravel Breeze
require __DIR__.'/auth.php';

// Rotas que exigem autenticação, mas não a verificação de licença
// (por exemplo, o dashboard e o perfil do utilizador)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rotas que exigem autenticação e verificação de licença
Route::middleware(['auth'])->group(function () {
    // Rota de recurso para o TicketController dentro do middleware de autenticação
        // Rotas públicas (usuários autenticados)
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');



    Route::post('tickets/{ticket}/message', [TicketController::class, 'storeMessage'])->name('tickets.message.store');
    Route::delete('tickets/files/{file}', [TicketController::class, 'deleteFile'])->name('tickets.file.delete');
    Route::post('tickets/{ticket}/files', [TicketController::class, 'storeFile'])->name('tickets.files.store');

});

// Rotas que apenas utilizadores com a role 'admin' podem aceder
Route::middleware(['auth',  'role:admin|tecnico|cliente'])->group(function () {
    Route::resource('licenses', LicenseController::class);
    Route::get('acessos', [LicenseController::class, 'activeUsers'])->name('acessos.index');
    Route::get('users/{user}/history', [UserController::class, 'history'])->name('users.history');

    Route::resource('roles', RoleController::class)->only(['index','store','destroy']);
    Route::resource('permissions', PermissionController::class)->only(['index','store','destroy']);
    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit');
    Route::post('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update');

    Route::resource('grupos', GrupoController::class);
    Route::resource('postos', PostoController::class);
    Route::resource('unidades', UnidadeController::class);


    Route::resource('empresas', EmpresaController::class);

    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('statuses', StatusController::class);
    Route::resource('urgencies', UrgencyController::class);
    Route::resource('users', UserController::class);

    Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');


});

Route::middleware(['auth', 'role:tecnico|admin'])->group(function () {
    Route::post('tickets/{ticket}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});



