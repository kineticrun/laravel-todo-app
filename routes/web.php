<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::get('/', [TodoController::class, 'index'])->name('todos.index');
Route::get('/completed', [TodoController::class, 'completed'])->name('todos.completed');
Route::get('/add', [TodoController::class, 'add'])->name('todos.add');
Route::get('/edit/{todo}', [TodoController::class, 'edit'])->name('todos.edit');
Route::patch('/edit/{todo}', [TodoController::class, 'update'])->name('todos.update');
Route::post('/add', [TodoController::class, 'store'])->name('todos.store');
Route::patch('/{todo}', [TodoController::class, 'check'])->name('todos.complete');
Route::delete('/{todo}', [TodoController::class, 'destroy'])->name('todos.delete');
Route::get('/set-locale/{locale}', [TodoController::class, 'setLocale'])->name('todos.set-locale');
