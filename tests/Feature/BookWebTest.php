<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookWebTest extends TestCase
{
    #[Test]
    public function the_homepage_redirects_to_the_book_list(): void
    {
        $this->get('/')->assertRedirect('/books');
    }

    #[Test]
    public function the_front_end_pages_load(): void
    {
        $this->get('/books')->assertOk()->assertSee('data-page="books-index"', false)->assertSee('id="search"', false);
        $this->get('/books/create')->assertOk()->assertSee('data-page="books-create"', false);
        $this->get('/books/1/edit')->assertOk()->assertSee('data-book-id="1"', false);
        $this->get('/books/1')->assertOk()->assertSee('data-page="books-show"', false);
    }
}
