<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BoardController as AdminBoardController; // Alias for admin controller
use App\Http\Controllers\Admin\CommentController; // Import CommentController
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController as AdminPostController; // Alias for admin controller
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuController; // Import MenuController
use App\Http\Controllers\Admin\MenuItemController; // Import MenuItemController
use App\Http\Controllers\BoardController as FrontendBoardController; // Alias for frontend board controller
use App\Http\Controllers\PageController as FrontendPageController; // Alias for frontend page controller
use App\Http\Controllers\PostController as FrontendPostController; // Alias for frontend post controller
use App\Http\Controllers\ProfileController;
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

// Redirect root to the posts index page
Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('index'); // Optional: give it a name like 'admin.index'

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
     })->name('dashboard');

     // User Management
     Route::resource('users', UserController::class);

     // Page Management
     Route::resource('pages', \App\Http\Controllers\Admin\PageController::class); // Use FQCN for clarity

     // Post Management
     Route::resource('posts', AdminPostController::class); // Use alias

     // Board Management
     Route::resource('boards', AdminBoardController::class); // Use alias

     // Article Management (within Boards or standalone)
     // Option 1: Standalone Article Management
     Route::resource('articles', ArticleController::class)->except(['show']); // Typically show is handled on front-end

     // Option 2: Nested under Boards (Uncomment if preferred)
     // Route::resource('boards.articles', ArticleController::class)->shallow();

     // Comment Management (Admin)
     Route::get('comments', [CommentController::class, 'index'])->name('comments.index');
     Route::patch('comments/{comment}', [CommentController::class, 'update'])->name('comments.update'); // For status updates primarily
     Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

     // Menu Management
     Route::resource('menus', MenuController::class);
     // Nested Menu Item Management (within a specific menu)
     Route::prefix('menus/{menu}/items')->name('menus.items.')->group(function () {
         Route::post('/', [MenuItemController::class, 'store'])->name('store');
         Route::put('/{item}', [MenuItemController::class, 'update'])->name('update'); // Use PUT for full replacement or PATCH for partial
         Route::delete('/{item}', [MenuItemController::class, 'destroy'])->name('destroy');
         Route::post('/update-order', [MenuItemController::class, 'updateOrder'])->name('updateOrder'); // Route for updating item order
     });


     // Add other admin routes here later (Settings)
 });

 // Frontend Routes
 Route::get('/boards', [FrontendBoardController::class, 'index'])->name('boards.index');
 Route::get('/boards/{slug}', [FrontendBoardController::class, 'show'])->name('boards.show'); // Shows articles within a board
 Route::get('/articles/{article}', [\App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show'); // Show individual article
 Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store'); // Store a new comment

 Route::get('/posts', [FrontendPostController::class, 'index'])->name('posts.index');
 Route::get('/posts/{slug}', [FrontendPostController::class, 'show'])->name('posts.show');

 // Page route should be last as a catch-all for slugs
 Route::get('/{slug}', [FrontendPageController::class, 'show'])->name('page.show');
