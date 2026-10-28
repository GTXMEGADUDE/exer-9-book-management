# Book Management System

A Laravel 13 application (run with Laragon) for managing a digital book collection: create, read, update, delete, and search books. It follows the structure of the *Laravel Project – CRUD, API Versioning, Email SMTP* guide: a versioned API resource controller (`App\Http\Controllers\Api\V1\BookController`), `Route::view` front-end pages, and `public/js/app.js` calling the API with `fetch`.

## Features

| Feature | Where |
| --- | --- |
| Add Book Record | `POST /api/books`, web: `/books/create` |
| View Book List | `GET /api/books`, web: `/books` table, `/books/{id}` details |
| Edit Book Record | `PUT`/`PATCH /api/books/{id}`, web: `/books/{id}/edit` |
| Delete Book Record | `DELETE /api/books/{id}`, web: **Delete** buttons |
| Search Function | `GET /api/books?search=...` (title or author, partial match), web: search bar |
| Validation | `$request->validate()` in the controller: all fields required, year must be 1000 to the current year |
| Notifications | API responses include a `message`; the web pages show it as a success notice |

## Setup (Laragon)

1. Start Laragon (MySQL).
2. Create the database `book_management` (HeidiSQL or `CREATE DATABASE book_management;`).
3. Install and configure:

   ```sh
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

   Set the database in `.env`, then run `php artisan config:clear`:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3307        # Laragon MySQL port (3306 on a default install)
   DB_DATABASE=book_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Create tables and sample books:

   ```sh
   php artisan migrate:fresh --seed
   ```

5. Run the app:

   ```sh
   php artisan serve --port=8001
   ```

   - Web UI: http://127.0.0.1:8001/books
   - API: http://127.0.0.1:8001/api/books (or http://127.0.0.1:8001/api/v1/books)

## API

The API is versioned under `/api/v1/books` (see `routes/api.php`). The same controller is also registered at `/api/books`, which is the address used in the lab test cases.

| Method | Endpoint | Success | Errors |
| --- | --- | --- | --- |
| GET | `/api/books` | 200, JSON array | |
| GET | `/api/books?search=Harry` | 200, JSON array (empty `[]` if no match) | |
| POST | `/api/books` | 201, `{ "message": "Book created successfully.", "data": {...} }` | 422 |
| GET | `/api/books/{id}` | 200, book object | 404 |
| PUT | `/api/books/{id}` | 200, `{ "message": "Book updated successfully.", "data": {...} }` | 422, 404 |
| PATCH | `/api/books/{id}` | 200, updates only the fields sent | 422, 404 |
| DELETE | `/api/books/{id}` | 200, `{ "message": "Book deleted successfully." }` | 404 |

Request body (JSON):

```json
{
    "title": "The Great Gatsby",
    "author": "F. Scott Fitzgerald",
    "genre": "Fiction",
    "year_published": 1925
}
```

A missing book returns `404` with `{ "message": "Book not found." }`.

## Front-End

| Page | Route | View |
| --- | --- | --- |
| Book list + search | `/books` | `resources/views/books/index.blade.php` |
| Add book | `/books/create` | `resources/views/books/create.blade.php` |
| Book details | `/books/{id}` | `resources/views/books/show.blade.php` |
| Edit book | `/books/{id}/edit` | `resources/views/books/edit.blade.php` |

The pages call the API from `public/js/app.js` and are styled by `public/css/app.css`.
