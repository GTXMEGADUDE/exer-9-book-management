@extends('layouts.app')

@section('title', 'Book List')

@section('content')
    <div class="card">
        <h1>Book List</h1>

        <div class="toolbar">
            <form class="search" method="GET" action="{{ route('books.index') }}">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by title or author">
                <button type="submit" class="btn btn-secondary">Search</button>
                @if ($search)
                    <a href="{{ route('books.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </form>
            <a href="{{ route('books.create') }}" class="btn">+ Add Book</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Year Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr>
                            <td>{{ $book->id }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->genre }}</td>
                            <td>{{ $book->year_published }}</td>
                            <td class="actions">
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('books.destroy', $book) }}"
                                      onsubmit="return confirm('Delete this book permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                @if ($search)
                                    No books match "{{ $search }}".
                                @else
                                    No books yet. Add your first book!
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $books->links('pagination::simple-bootstrap-4') }}</div>
    </div>
@endsection
