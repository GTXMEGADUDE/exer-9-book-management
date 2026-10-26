<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::view('/books', 'books.index')
    ->name('books.index');

Route::view('/books/create', 'books.create')
    ->name('books.create');

Route::view('/books/{id}/edit', 'books.edit')
    ->whereNumber('id')
    ->name('books.edit');

Route::view('/books/{id}', 'books.show')
    ->whereNumber('id')
    ->name('books.show');
