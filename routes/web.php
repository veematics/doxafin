<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AppFeatureController;
use App\Http\Controllers\AppSetupController;
use App\Http\Controllers\RoleController;  // Add this line
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::get('/', [WelcomeController::class, 'index'])
    ->middleware('guest')
    ->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
    // --- Profile Routes ---
    // These seem fine as they are. Grouping them can be slightly cleaner.
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show'); // Route name: profile.show
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('password'); // Route name: profile.password
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('avatar'); // Route name: profile.avatar
    });

    // Coming Soon Route
    Route::get('/coming-soon', function () {
        return view('coming-soon');
    })->name('coming-soon');
    // --- App Settings Routes ---
    Route::group(['middleware' => ['auth'], 'prefix' => 'appsetting', 'as' => 'appsetting.'], function () {
        Route::resource('users', UserManagementController::class)->except(['show']);
        Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
             ->name('users.toggle-status');
        
        // Features Management Routes
        Route::resource('appfeature', AppFeatureController::class)->except(['show']);
        
        // App Setup Routes
        Route::get('/appsetup', [AppSetupController::class, 'index'])->name('appsetup.index');
        Route::put('/appsetup', [AppSetupController::class, 'update'])->name('appsetup.update'); // Removed {id} parameter
    

        // Menu Management Routes
        Route::resource('menu', MenuController::class);
        Route::post('menu/{menu}/structure', [MenuController::class, 'structure'])->name('menu.structure');
        Route::resource('menu-items', \App\Http\Controllers\MenuItemController::class)->except(['index', 'create', 'edit']);
       
        // Role Management Routes
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::get('roles/{role}/members', [RoleController::class, 'members'])->name('roles.members');
        Route::get('roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
        Route::post('roles/{role}/add-members', [RoleController::class, 'addMembers'])
            ->name('roles.add-members');
        Route::post('roles/{role}/remove-member/{user}', [RoleController::class, 'removeMember'])
            ->name('roles.remove-member');
        Route::post('/appsetting/roles/{role}/duplicate', [RoleController::class, 'duplicate'])->name('roles.duplicate');
    });


});

require __DIR__.'/auth.php';


