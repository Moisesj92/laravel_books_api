<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\Book;

class BooksApiTest extends TestCase
{

  use RefreshDatabase;

  /** @test */
  function can_get_all_books()
  {

    $books = Book::factory(4)->create();

    $response = $this->getJson(route('books.index'));

    $response->assertJsonFragment([
      'title' => $books[0]->title,
    ]);

  }

  /** @test */
  function can_get_one_book()
  {
    $book = Book::factory()->create();

    $response = $this->getJson(route('books.show', $book));

    $response->assertJsonFragment([
      'title' => $book->title,
    ]);

  }

  /** @test */
  function can_create_a_book()
  {

    $this->postJson(route('books.store'), [])->assertJsonValidationErrorFor('title');
    $this->postJson(route('books.store'),[
      'title' => 'My New Book'
    ])->assertJsonFragment([
      'title' => 'My New Book'
    ]);
    $this->assertDatabaseHas('books', [
      'title' => 'My New Book'
    ]);

  }

  /** @test */
  function can_update_a_book()
  {
    $book = Book::factory()->create();

    $this->patchJson(route('books.update', $book), [])->assertJsonValidationErrorFor('title');
    $this->patchJson(route('books.update', $book), [
      'title' => 'edited book'
    ])->assertJsonFragment([
      'title' => 'edited book'
    ]);
    $this->assertDatabaseHas('books', [
      'title' => 'edited book'
    ]);

  }

  /** @test */
  function can_delete_a_book()
  {
    $book = Book::factory()->create();

    $this->deleteJson(route('books.destroy', $book))->assertNoContent();
    $this->assertDatabaseCount('books', 0);

  }

}
