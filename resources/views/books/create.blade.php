@extends('layouts.app')

@section('title', 'Add Book')

@section('content')
    <div class="card">
        <h1>Add Book</h1>
        <form method="POST" action="{{ route('books.store') }}" novalidate>
            @include('books._form', ['submitLabel' => 'Save Book'])
        </form>
    </div>
@endsection
