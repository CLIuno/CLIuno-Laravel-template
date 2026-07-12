<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {

    // Users
    Route::get('/users/current', [UserController::class, 'getCurrent']);
    Route::patch('/users/current', [UserController::class, 'updateCurrent']);
    Route::delete('/users/current', [UserController::class, 'deleteCurrent']);
    Route::get('/users/username/{username}', [UserController::class, 'getByUsername']);
    Route::get('/users/posts', [UserController::class, 'getPostsByUserId']);
    Route::get('/users/role', [UserController::class, 'getRolesByUserId']);
    Route::get('/users/{id}', [UserController::class, 'getById']);

    // Admin-only user routes
    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'getAll']);
        Route::patch('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'delete']);
    });

    // Todos
    Route::get('/todos/current-user', [TodoController::class, 'currentUser']);
    Route::get('/todos', [TodoController::class, 'index']);
    Route::get('/todos/{id}', [TodoController::class, 'show']);
    Route::post('/todos', [TodoController::class, 'store']);
    Route::patch('/todos/{id}', [TodoController::class, 'update']);
    Route::delete('/todos/{id}', [TodoController::class, 'destroy']);
    Route::patch('/todos/{id}/toggle', [TodoController::class, 'toggleComplete']);

    // Posts
    Route::get('/posts/current-user', [PostController::class, 'currentUser']);
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::get('/posts/{id}/user', [PostController::class, 'getUserByPostId']);

    // Comments on Posts
    Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
    Route::patch('/posts/{postId}/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/posts/{postId}/comments/{id}', [CommentController::class, 'destroy']);

    // Follows
    Route::post('/users/{userId}/follow', [FollowController::class, 'follow']);
    Route::delete('/users/{userId}/follow', [FollowController::class, 'unfollow']);
    Route::get('/users/{userId}/followers', [FollowController::class, 'getFollowers']);
    Route::get('/users/{userId}/following', [FollowController::class, 'getFollowing']);
    Route::get('/users/{userId}/is-following', [FollowController::class, 'isFollowing']);

    // Roles (admin only)
    Route::middleware('admin')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::patch('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
        Route::get('/roles/{id}/users', [RoleController::class, 'getUsersByRoleId']);
    });
});
