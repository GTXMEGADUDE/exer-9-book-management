@extends('layouts.app')
@section('title', 'Books')
@section('page', 'books-index')
@section('content')
<div class="page-header"><div><p class="eyebrow">BOOK MANAGEMENT</p><h1>Book List</h1><p>Search, view, edit, or remove books in the collection.</p></div><a class="btn" href="{{ route('books.create') }}">+ Add Book</a></div>
<div id="bookNotice" class="notice" role="status" hidden></div>
<section class="glass-card table-card"><div class="section-heading"><h2>Saved Books</h2><button type="button" class="btn btn-secondary" id="refreshBooks">Refresh</button></div>
<form id="searchForm" class="search-bar" role="search"><input id="search" name="search" type="search" placeholder="Search by title or author"><button type="submit">Search</button><button type="button" class="btn btn-secondary" id="clearSearch">Clear</button></form>
<div class="table-responsive"><table><thead><tr><th>ID</th><th>Title</th><th>Author</th><th>Genre</th><th>Year</th><th>Actions</th></tr></thead>
<tbody id="bookTableBody"><tr><td colspan="6" class="empty-state">Loading books...</td></tr></tbody></table></div></section>
@endsection
