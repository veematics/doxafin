<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AppFeatureController;  // Add this line
use App\Http\Controllers\AppSetupController;
use Illuminate\Support\Facades\Route;

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

            Route::resource('menu', \App\Http\Controllers\MenuController::class);
            Route::post('menu/{menu}/structure', [\App\Http\Controllers\MenuController::class, 'saveStructure'])->name('menu.structure');
            Route::resource('menu-items', \App\Http\Controllers\MenuItemController::class)->except(['index', 'create', 'edit']);
       
    });


});

require __DIR__.'/auth.php';
