<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RemoveController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\Group_userController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'list'])->name('home');
Route::get('/appeals', [HomeController::class, 'appeal_list']);

Route::get('/user', [UserController::class, 'list']);
Route::get('/user/{id}', [UserController::class, 'profile']);
Route::get('/user/edit/{id}', [UserController::class, 'edit']);
Route::post('/user/edit/{id}', [UserController::class, 'update'])->name('user.update');

Route::get('/user/delete/{id}', [UserController::class, 'delete']);
Route::get('/user/remove/{id}', [UserController::class, 'remove']);

Route::post('/{obj_name}/delete/{id}', [RemoveController::class, 'delete'])->name('delete_create');
Route::post('/{obj_name}/remove/{id}', [RemoveController::class, 'remove'])->name('remove_create');

Route::get('/restore/{id}', [RemoveController::class, 'pre_restore']);
Route::get('/appeal/{id}', [RemoveController::class, 'pre_review']);
Route::post('/appeal/{id}', [RemoveController::class, 'answer_appeal'])->name('appeal_review');

Route::post('/restore/active/{id}', [RemoveController::class, 'active_restore'])->name('active_restore');
Route::post('/restore/removed/{id}', [RemoveController::class, 'remove_restore'])->name('remove_restore');
Route::post('/restore/deleted/{id}', [RemoveController::class, 'delete_restore'])->name('delete_restore');

Route::get('/group', [GroupController::class, 'list']);

Route::get('/group/create', [GroupController::class, 'create_form']);
Route::post('/group/create', [GroupController::class, 'create'])->name('group.create');

Route::get('/group/{id}', [GroupController::class, 'profile']);
Route::get('/group/edit/{id}', [GroupController::class, 'edit']);
Route::post('/group/edit/{id}', [GroupController::class, 'update'])->name('group.update');
Route::get('/group/delete/{id}', [GroupController::class, 'delete']);
Route::get('/group/remove/{id}', [GroupController::class, 'remove']);

Route::get('/member/create/{id}', [Group_userController::class, 'create_form']);
Route::post('/member/create/{id}', [Group_userController::class, 'create'])->name('group_user.create');

Route::get('/group/accept/{id}', [Group_userController::class, 'accept']);
Route::get('/group/left/{id}', [Group_userController::class, 'left']);
Route::get('/group/join/{id}', [Group_userController::class, 'join']);

Route::get('/group/members/{id}', [Group_userController::class, 'members']);
Route::get('/group/member/{id}', [Group_userController::class, 'profile']);

Route::get('/member/kick/{id}', [Group_userController::class, 'kick']);
Route::get('/member/ban/{id}', [Group_userController::class, 'ban']);
Route::get('/member/edit/{id}', [Group_userController::class, 'edit']);
Route::get('/member/admiss/{id}', [Group_userController::class, 'admiss']);

Route::post('/member/edit/{id}', [Group_userController::class, 'update'])->name('group_user.update');

Route::get('/post/{id}', [PostController::class, 'profile']);
Route::get('/post/create/{id}', [PostController::class, 'create_form']);
Route::post('/post/create/{id}', [PostController::class, 'create'])->name('post.create');
Route::get('/post/edit/{id}', [PostController::class, 'edit']);
Route::post('/post/edit/{id}', [PostController::class, 'update'])->name('post.update');
Route::get('/post/delete/{id}', [PostController::class, 'delete']);
Route::get('/post/remove/{id}', [PostController::class, 'remove']);

Route::get('/comment/{id}', [CommentController::class, 'profile']);
Route::get('/comment/create/{id}', [CommentController::class, 'create_form']);
Route::post('/comment/create/{id}', [CommentController::class, 'create'])->name('comment.create');
Route::get('/comment/edit/{id}', [CommentController::class, 'edit']);
Route::post('/comment/edit/{id}', [CommentController::class, 'update'])->name('comment.update');
Route::get('/comment/delete/{id}', [CommentController::class, 'delete']);
Route::get('/comment/remove/{id}', [CommentController::class, 'remove']);

Auth::routes();


