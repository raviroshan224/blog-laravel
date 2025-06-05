<?php
// routes/api.php - Laravel 10 compatible

use App\Http\Controllers\ExcelImportExportController;
use App\Http\Controllers\PublicPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExcelExportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/posts', [PublicPostController::class, 'posts']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // User management routes
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole']);
    Route::post('/users/{user}/remove-role', [UserController::class, 'removeRole']);
    Route::get('/roles', [UserController::class, 'getRoles']);

    // CRUD routes
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('posts', PostController::class);
    
    // Import/Export Routes
    Route::get('/excel/export-categories', [ExcelImportExportController::class, 'export_categories']);
    Route::get('/excel/export-posts', [ExcelImportExportController::class, 'export_posts']);
    Route::post('/excel/import-categories', [ExcelImportExportController::class, 'import_categories'])->name('import.categories');
    
});

