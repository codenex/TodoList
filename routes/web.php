<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;


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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [TodoController::class, 'index']);
Route::post('/add-task', [TodoController::class, 'create'])->name('add_task');
Route::post('/mark-complete-task', [TodoController::class, 'markTaskComplete'])->name('mark_task_complete');
Route::get('/get-all-tasks', [TodoController::class, 'showAllTasks'])->name('get_all_tasks');
Route::post('/delete-task', [TodoController::class, 'deleteTask'])->name('dalete_task');