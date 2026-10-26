@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
    <div class="card">
        <h1>Edit Book</h1>
        <form method="POST" action="{{ route('books.update', $book) }}" novalidate>
            @method('PUT')
            @include('books._form', ['submitLabel' => 'Update Book'])
        </form>
    </div>
@endsection
