<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $books = Book::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%");
            })
            ->get();

        return response()->json($books, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:100',
            'year_published' => 'required|integer|min:1000|max:'.date('Y'),
        ]);

        $book = Book::create($data);

        return response()->json([
            'message' => 'Book created successfully.',
            'data' => $book,
        ], 201);
    }

    public function show(Book $book)
    {
        return response()->json($book, 200);
    }

    public function update(
        Request $request,
        Book $book
    ) {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'genre' => 'sometimes|required|string|max:100',
            'year_published' => 'sometimes|required|integer|min:1000|max:'.date('Y'),
        ]);

        $book->update($data);

        return response()->json([
            'message' => 'Book updated successfully.',
            'data' => $book,
        ], 200);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.',
        ], 200);
    }
}
