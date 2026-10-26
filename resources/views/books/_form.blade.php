<div class="form-grid">
<div class="form-group"><label for="title">Title</label><input id="title" name="title" maxlength="255" required></div>
<div class="form-group"><label for="author">Author</label><input id="author" name="author" maxlength="255" required></div>
<div class="form-group"><label for="genre">Genre</label><input id="genre" name="genre" maxlength="100" required></div>
<div class="form-group"><label for="year_published">Year Published</label><input id="year_published" name="year_published" type="number" min="1000" max="{{ date('Y') }}" placeholder="e.g. 1997" required></div>
</div>
