<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('/dashboard', [TodoController::class, 'index'])->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Todos
    Route::get("/todo/detail/{id}", [TodoController::class, 'todo_detail'])->name("todo.todo_detail");
    Route::get("/todo/archived", [TodoController::class, 'todo_archived'])->name("todo.todo_archived");
    Route::post("/todo/update", [TodoController::class, 'update'])->name("todo.update");
    Route::post("/todo/new", [TodoController::class, 'new'])->name("todo.new");
    Route::post("/todo/done", [TodoController::class, 'done'])->name("todo.done");
    Route::post("/todo/archive", [TodoController::class, 'archive'])->name("todo.archive");
    Route::post("/todo/delete", [TodoController::class, 'permaDelete'])->name("todo.permaDelete");
});

require __DIR__.'/auth.php';
