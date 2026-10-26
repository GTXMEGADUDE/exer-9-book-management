# Book Management System

A Laravel 13 application (run with Laragon) for managing a digital book collection: create, read, update, delete, and search books. It has a JSON REST API (tested with Postman) and a simple web interface.

## Features

| Feature | Where |
| --- | --- |
| Add Book Record | `POST /api/books`, web: **+ Add Book** |
| View Book List | `GET /api/books`, web: `/books` table |
| Edit Book Record | `PUT /api/books/{id}`, web: **Edit** |
| Delete Book Record | `DELETE /api/books/{id}`, web: **Delete** |
| Search Function | `GET /api/books?search=...` (title or author, partial match), web: search box |
| Validation | `App\Http\Requests\BookRequest`: all fields required, year must be a 4-digit year not in the future |
| Notifications | API responses include a `message`; the web UI shows a green success banner |

## Setup (Laragon)

1. Start Laragon (MySQL).
2. Create the database `book_management` (HeidiSQL or `CREATE DATABASE book_management;`).
3. Install and configure:

   ```sh
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

   Set the database in `.env`:

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
   - API: http://127.0.0.1:8001/api/books

## API

| Method | Endpoint | Success | Errors |
| --- | --- | --- | --- |
| GET | `/api/books` | 200, JSON array | |
| GET | `/api/books?search=Harry` | 200, JSON array (empty `[]` if no match) | |
| POST | `/api/books` | 201, `{ "message": "Book created successfully.", "data": {...} }` | 422 |
| GET | `/api/books/{id}` | 200, book object | 404 |
| PUT | `/api/books/{id}` | 200, `{ "message": "Book updated successfully.", "data": {...} }` | 422, 404 |
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

## Postman Test Cases

Import `postman/Book-Management-System.postman_collection.json` into Postman. It contains requests TC01-TC21, each with a status-code check. The `baseUrl` collection variable defaults to `http://127.0.0.1:8001`.

- Run `php artisan migrate:fresh --seed` before TC01 so book IDs match.
- TC12 deletes book 1, so run `php artisan migrate:fresh --seed` again before TC19-TC21.

## Automated Tests

```sh
php artisan test
```

Feature tests in `tests/Feature/BookApiTest.php` and `tests/Feature/BookWebTest.php` cover every test case.
