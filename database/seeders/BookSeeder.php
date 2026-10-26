<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'genre' => 'Fiction', 'year_published' => 1960],
            ["title" => "Harry Potter and the Philosopher's Stone", 'author' => 'J.K. Rowling', 'genre' => 'Fantasy', 'year_published' => 1997],
            ['title' => 'Harry Potter and the Chamber of Secrets', 'author' => 'J.K. Rowling', 'genre' => 'Fantasy', 'year_published' => 1998],
            ['title' => '1984', 'author' => 'George Orwell', 'genre' => 'Dystopian', 'year_published' => 1949],
            ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'genre' => 'Fantasy', 'year_published' => 1937],
            ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'genre' => 'Romance', 'year_published' => 1813],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
