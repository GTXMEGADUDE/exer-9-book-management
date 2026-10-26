<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $books = Book::search($request->query('search'))
            ->orderBy('id')
            ->get();

        return response()->json($books, 200);
    }

    public function store(BookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return response()->json([
            'message' => 'Book created successfully.',
            'data' => $book,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (! $book) {
            return $this->notFound();
        }

        return response()->json($book, 200);
    }

    public function update(BookRequest $request, string $id): JsonResponse
    {
        $book = Book::find($id);

        if (! $book) {
            return $this->notFound();
        }

        $book->update($request->validated());

        return response()->json([
            'message' => 'Book updated successfully.',
            'data' => $book,
        ], 200);
    }

    public function destroy(string $id): JsonResponse
    {
        $book = Book::find($id);

        if (! $book) {
            return $this->notFound();
        }

        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully.',
        ], 200);
    }

    private function notFound(): JsonResponse
    {
        return response()->json([
            'message' => 'Book not found.',
        ], 404);
    }
}
