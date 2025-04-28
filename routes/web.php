<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AppFeatureController;
use App\Http\Controllers\AppSetupController;
use App\Http\Controllers\RoleController;  // Add this line
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\InboxMessageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CsvDataController;
use App\Http\Controllers\PurchaseOrderController;

Route::get('/', [WelcomeController::class, 'index'])
    ->middleware('guest')
    ->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
    // Media Viewing Routes
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('view/{filename}', [\App\Http\Controllers\MediaController::class, 'show'])->name('view');
    });

    // --- Profile Routes ---
    // These seem fine as they are. Grouping them can be slightly cleaner.
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show'); // Route name: profile.show
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('password'); // Route name: profile.password
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('avatar'); // Route name: profile.avatar
    });

    // Debug Route
    Route::get('/debug', function () {
        return view('debug');
    })->name('debug');
    Route::post('debug/rebuild-cache', function() {
        \App\Helpers\FeatureAccess::rebuildCache(auth()->id());
        return back()->with('success', 'Cache rebuilt successfully');
    })->name('debug.rebuild-cache');
     


    // Coming Soon Route
    Route::get('/coming-soon', function () {
        return view('coming-soon');
    })->name('coming-soon');
    // --- App Settings Routes ---
    Route::group(['middleware' => ['auth'], 'prefix' => 'appsetting', 'as' => 'appsetting.'], function () {
        Route::resource('users', UserManagementController::class)->except(['show']);
        Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])
             ->name('users.toggle-status');
        
        // Remove this duplicate route
        // Route::resource('users', \App\Http\Controllers\Appsetting\UserController::class);
        
        // Features Management Routes
        Route::resource('appfeature', AppFeatureController::class)->except(['show']);
        
        // App Setup Routes
        Route::get('/appsetup', [AppSetupController::class, 'index'])->name('appsetup.index');
        Route::put('/appsetup', [AppSetupController::class, 'update'])->name('appsetup.update');
    

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
    }); // End of appsetting group

    // Add playground routes
    // Playground routes
    Route::prefix('playground')->name('playground.')->middleware(['auth'])->group(function () {
        Route::get('/', function () {
            return view('playground.index');
        })->name('index');
        
        Route::get('/roles', function () {
            return view('playground.roles');
        })->name('roles');
    
        Route::get('/select2', function () {
            return view('playground.select2');
        })->name('select2');
    
        Route::get('/ckeditor', function () {
            return view('playground.demockeditor');
        })->name('ckeditor');
        Route::get('/memberroles', function () {
            return view('playground.demockeditor');
        })->name('memberroles');
    });

    // Inbox routes
    Route::prefix('inbox')->name('inbox.')->group(function () {
        Route::get('/', [InboxMessageController::class, 'index'])->name('index');
        Route::get('/sent', [InboxMessageController::class, 'sent'])->name('sent');
        Route::get('/trash', [InboxMessageController::class, 'trash'])->name('trash');
        Route::post('/store', [InboxMessageController::class, 'store'])->name('store');
        
        // Then place wildcard routes
        Route::get('/{message}', [InboxMessageController::class, 'show'])->name('show');
        Route::post('/{message}/trash', [InboxMessageController::class, 'moveToTrash'])->name('move-to-trash');
        Route::get('/{message}/reply', [InboxMessageController::class, 'replyForm'])->name('reply.form');
        Route::post('/{message}/reply', [InboxMessageController::class, 'reply'])->name('reply');
        Route::get('/{message}/thread', [InboxMessageController::class, 'thread'])->name('thread');
        
        // Add recover route
        Route::post('/{message}/recover', [InboxMessageController::class, 'recoverMessage'])
            ->name('recover');
        // Fix this route - removing the duplicate 'inbox/' prefix
        Route::post('/{message}/mark-as-read', [InboxMessageController::class, 'markAsRead'])->name('mark-as-read');
    });

    
    
<<<<<<< HEAD
    // Client routes
    Route::prefix('clients')->name('clients.')->group(function () {
        // Base client routes
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');  // Add this line

        // Contact search route
        Route::get('/clients/contacts/search', [ClientController::class, 'searchContacts'])
            ->name('clients.contacts.search');
        Route::get('/clients/{client}', [ClientController::class, 'getClientDetails'])->name('clients.details');

        // Independent contact creation route
        Route::get('/contacts/create', [ContactController::class, 'createIndependent'])->name('contacts.create-independent');
        Route::post('/contacts', [ContactController::class, 'storeIndependent'])->name('contacts.store-independent');
        Route::get('/contacts/search', [ClientController::class, 'searchContacts'])->name('contacts.search');  
        // Nested client and contact routes
        Route::prefix('{client}')->group(function () {
            
            Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
            Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
             
            Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
            Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
            Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
            Route::patch('/contacts/{contact}/make-primary', [ContactController::class, 'makePrimary'])
                ->name('contacts.make-primary');
            // Contact search route
   
            // Client-specific routes
            Route::get('/edit', [ClientController::class, 'edit'])->name('edit');
            Route::put('/', [ClientController::class, 'update'])->name('update');
            Route::get('/', [ClientController::class, 'show'])->name('show');

        });

    });
=======
  
>>>>>>> 08efe325cdca44eb6765054f06a788b15a786eab
    // Update the CSV data routes
    // CSV Data Routes - simplified version
    Route::middleware('auth')->group(function () {
        Route::resource('csv-data', CsvDataController::class, [
            'parameters' => ['csv-data' => 'csvData'] // Define the parameter name explicitly
        ])->except(['show'])->names([
            'index' => 'csv-data.index',
            'create' => 'csv-data.create',
            'store' => 'csv-data.store',
            'edit' => 'csv-data.edit',
            'update' => 'csv-data.update',
            'destroy' => 'csv-data.destroy',
        ]);
        Route::get('csv-data/{csvData}/view', [CsvDataController::class, 'view'])->name('csv-data.view');
        
    });

    // Purchase Order Routes
    Route::middleware(['auth'])->prefix('po')->name('purchase-orders.')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::resource('purchase-orders', PurchaseOrderController::class)
            ->except(['create', 'edit'])
            ->names([
                'index' => 'purchase-orders.index',
                'store' => 'purchase-orders.store',
                'show' => 'purchase-orders.show',
                'update' => 'purchase-orders.update',
                'destroy' => 'purchase-orders.destroy',
            ]);
        
        Route::get('/add', [PurchaseOrderController::class, 'create'])
            ->name('add');
        Route::get('/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])
            ->name('edit');
        Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{purchaseOrder}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::put('/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('update');
        Route::delete('/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{purchaseOrder}/services', [PurchaseOrderController::class, 'services'])
            ->name('services');
        });
    // Route::middleware('auth')->prefix('api')->group(function () {
    //     Route::get('/clients/{client}', [ClientController::class, 'getClientDetails']);
    // });
});
require __DIR__.'/auth.php';






