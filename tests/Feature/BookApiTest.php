<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    private function validBook(array $overrides = []): array
    {
        return array_merge([
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'genre' => 'Fiction',
            'year_published' => 1925,
        ], $overrides);
    }

    #[Test]
    public function it_adds_a_book_with_valid_details(): void
    {
        $this->postJson('/api/books', $this->validBook())
            ->assertCreated()
            ->assertJsonPath('message', 'Book created successfully.')
            ->assertJsonPath('data.title', 'The Great Gatsby')
            ->assertJsonPath('data.year_published', 1925);

        $this->assertDatabaseHas('books', ['title' => 'The Great Gatsby']);
    }

    #[Test]
    public function it_rejects_a_book_with_a_missing_required_field(): void
    {
        foreach (['title', 'author', 'genre'] as $field) {
            $this->postJson('/api/books', $this->validBook([$field => '']))
                ->assertUnprocessable()
                ->assertJsonValidationErrors([$field]);
        }

        $this->assertDatabaseCount('books', 0);
    }

    #[Test]
    public function it_rejects_an_invalid_publication_year(): void
    {
        foreach (['abcd', 999, now()->year + 1] as $year) {
            $this->postJson('/api/books', $this->validBook(['year_published' => $year]))
                ->assertUnprocessable()
                ->assertJsonValidationErrors(['year_published']);
        }
    }

    #[Test]
    public function it_returns_json_validation_errors_without_an_accept_header(): void
    {
        $this->post('/api/books', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'author', 'genre', 'year_published']);
    }

    #[Test]
    public function it_lists_all_books(): void
    {
        Book::factory()->count(3)->create();

        $this->getJson('/api/books')
            ->assertOk()
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_shows_a_specific_book(): void
    {
        $book = Book::factory()->create();

        $this->getJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('id', $book->id)
            ->assertJsonPath('title', $book->title);
    }

    #[Test]
    public function it_returns_404_for_a_nonexistent_book(): void
    {
        $this->getJson('/api/books/99999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Book not found.');

        $this->putJson('/api/books/99999', $this->validBook())
            ->assertNotFound()
            ->assertJsonPath('message', 'Book not found.');

        $this->deleteJson('/api/books/99999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Book not found.');
    }

    #[Test]
    public function it_updates_an_existing_book(): void
    {
        $book = Book::factory()->create();

        $this->putJson("/api/books/{$book->id}", $this->validBook(['title' => 'Updated Title']))
            ->assertOk()
            ->assertJsonPath('message', 'Book updated successfully.')
            ->assertJsonPath('data.title', 'Updated Title');

        $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'Updated Title']);
    }

    #[Test]
    public function it_rejects_an_update_with_missing_required_fields(): void
    {
        $book = Book::factory()->create();

        $this->putJson("/api/books/{$book->id}", ['title' => '', 'author' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['title', 'author']);
    }

    #[Test]
    public function it_partially_updates_a_book_with_patch(): void
    {
        $book = Book::factory()->create(['genre' => 'Fiction']);

        $this->patchJson("/api/books/{$book->id}", ['genre' => 'Classic'])
            ->assertOk()
            ->assertJsonPath('data.genre', 'Classic')
            ->assertJsonPath('data.title', $book->title);
    }

    #[Test]
    public function the_versioned_v1_endpoints_work(): void
    {
        $book = Book::factory()->create();

        $this->getJson('/api/v1/books')->assertOk()->assertJsonCount(1);
        $this->getJson("/api/v1/books/{$book->id}")->assertOk()->assertJsonPath('id', $book->id);
        $this->getJson('/api/v1/books/99999')->assertNotFound()->assertJsonPath('message', 'Book not found.');
    }

    #[Test]
    public function it_deletes_a_book_and_it_can_no_longer_be_retrieved(): void
    {
        $book = Book::factory()->create();

        $this->deleteJson("/api/books/{$book->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Book deleted successfully.');

        $this->getJson("/api/books/{$book->id}")->assertNotFound();
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    #[Test]
    public function it_searches_books_by_title_or_author(): void
    {
        Book::factory()->create(['title' => "Harry Potter and the Philosopher's Stone", 'author' => 'J.K. Rowling']);
        Book::factory()->create(['title' => 'Harry Potter and the Chamber of Secrets', 'author' => 'J.K. Rowling']);
        Book::factory()->create(['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien']);

        $this->getJson('/api/books?search=Harry%20Potter')->assertOk()->assertJsonCount(2);
        $this->getJson('/api/books?search=J.K.%20Rowling')->assertOk()->assertJsonCount(2);
        $this->getJson('/api/books?search=Hobb')->assertOk()->assertJsonCount(1)->assertJsonPath('0.title', 'The Hobbit');
        $this->getJson('/api/books?search=NoSuchBook')->assertOk()->assertExactJson([]);
    }
}
