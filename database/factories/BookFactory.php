<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => rtrim(fake()->sentence(3), '.'),
            'author' => fake()->name(),
            'genre' => fake()->randomElement(['Fiction', 'Fantasy', 'Mystery', 'Science Fiction', 'Biography', 'History', 'Romance']),
            'year_published' => fake()->numberBetween(1900, now()->year),
        ];
    }
}
