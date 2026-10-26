@if ($errors->any())
    <div class="alert alert-danger">
        Please fix the following errors:
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@csrf

<div class="field">
    <label for="title">Title</label>
    <input type="text" id="title" name="title" value="{{ old('title', $book->title) }}" class="@error('title') invalid @enderror" maxlength="255">
    @error('title') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="author">Author</label>
    <input type="text" id="author" name="author" value="{{ old('author', $book->author) }}" class="@error('author') invalid @enderror" maxlength="255">
    @error('author') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="genre">Genre</label>
    <input type="text" id="genre" name="genre" value="{{ old('genre', $book->genre) }}" class="@error('genre') invalid @enderror" maxlength="100">
    @error('genre') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="year_published">Year Published</label>
    <input type="number" id="year_published" name="year_published" value="{{ old('year_published', $book->year_published) }}" class="@error('year_published') invalid @enderror" min="1000" max="{{ now()->year }}">
    @error('year_published') <div class="error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn">{{ $submitLabel }}</button>
    <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a>
</div>
