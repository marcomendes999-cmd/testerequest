<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\TipoController;
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

// Rotas públicas (sem autenticação)
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação do Laravel Breeze
require __DIR__.'/auth.php';

// Rotas que exigem autenticação, mas não a verificação de licença
// (por exemplo, o dashboard e o perfil do utilizador)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rotas que exigem autenticação e verificação de licença
Route::middleware(['auth', 'license.valid'])->group(function () {
    // Rota de recurso para o TicketController dentro do middleware de autenticação
        // Rotas públicas (usuários autenticados)
    Route::get('tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::patch('tickets/{ticket}/approval', [TicketController::class, 'updateApproval'])->name('tickets.approval.update');



    Route::post('tickets/{ticket}/message', [TicketController::class, 'storeMessage'])->name('tickets.message.store');
    Route::post('tickets/{ticket}/files', [TicketController::class, 'storeFile'])->name('tickets.files.store');
    Route::get('tickets/{ticket}/files/{file}/download', [TicketController::class, 'downloadFile'])->name('tickets.files.download');
    Route::delete('tickets/{ticket}/files/{file}', [TicketController::class, 'deleteFile'])->name('tickets.file.delete');

});

// Rotas que apenas utilizadores com a role 'admin' podem aceder
Route::middleware(['auth',  'role:admin|tecnico|cliente'])->group(function () {
    Route::resource('licenses', LicenseController::class)->middleware('license.owner');
    Route::get('acessos', [LicenseController::class, 'activeUsers'])->middleware('license.owner')->name('acessos.index');
    Route::get('users/{user}/history', [UserController::class, 'history'])->name('users.history');

    Route::resource('roles', RoleController::class)->only(['index','store','destroy']);
    Route::resource('permissions', PermissionController::class)->only(['index','store','destroy']);
    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])->middleware('role:admin')->name('users.roles.edit');
    Route::post('users/{user}/roles', [UserRoleController::class, 'update'])->middleware('role:admin')->name('users.roles.update');

    Route::resource('grupos', GrupoController::class);
    Route::resource('postos', PostoController::class);
    Route::resource('unidades', UnidadeController::class);


    Route::resource('empresas', EmpresaController::class);

    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('statuses', StatusController::class);
    Route::resource('urgencies', UrgencyController::class);
    Route::resource('users', UserController::class)->middleware('role:admin');
    Route::resource('tipos', TipoController::class)->middleware('role:admin');
    Route::post('users/{user}/resend-verification', [UserController::class, 'resendVerification'])
        ->middleware('role:admin')
        ->name('users.verification.resend');

    Route::put('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');


});

Route::middleware(['auth', 'license.valid'])->group(function () {
    Route::post('tickets/{ticket}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});
