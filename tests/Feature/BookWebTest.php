<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookWebTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_displays_the_book_table_with_search(): void
    {
        Book::factory()->create(['title' => 'The Hobbit']);
        Book::factory()->create(['title' => 'Dune']);

        $this->get('/books')->assertOk()->assertSee('The Hobbit')->assertSee('Dune');
        $this->get('/books?search=Hobbit')->assertOk()->assertSee('The Hobbit')->assertDontSee('Dune');
    }

    #[Test]
    public function it_creates_updates_and_deletes_with_success_notifications(): void
    {
        $data = ['title' => 'Dune', 'author' => 'Frank Herbert', 'genre' => 'Science Fiction', 'year_published' => 1965];

        $this->post('/books', $data)->assertRedirect('/books')->assertSessionHas('success', 'Book created successfully.');
        $book = Book::firstWhere('title', 'Dune');

        $this->put("/books/{$book->id}", [...$data, 'title' => 'Dune Messiah'])
            ->assertRedirect('/books')->assertSessionHas('success', 'Book updated successfully.');

        $this->delete("/books/{$book->id}")
            ->assertRedirect('/books')->assertSessionHas('success', 'Book deleted successfully.');
        $this->assertDatabaseCount('books', 0);
    }

    #[Test]
    public function it_shows_validation_errors_on_the_form(): void
    {
        $this->from('/books/create')->post('/books', [])
            ->assertRedirect('/books/create')
            ->assertSessionHasErrors(['title', 'author', 'genre', 'year_published']);
    }
}
