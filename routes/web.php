<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return redirect('/tasks');
});

// router for users
Route::get('/users', function () {
    return view('users');
})->name('users.index');

Route::post('/users', [UserController::class, 'store'])->name('users.store');


// router for task
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::put('/tasksStatus/{id}', [TaskController::class, 'updateStatus']);
Route::delete('/tasks/{id}', [TaskController::class, 'deleted'])->name('tasks.delete');
