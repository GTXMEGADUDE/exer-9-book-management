<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'genre' => ['required', 'string', 'max:100'],
            'year_published' => ['required', 'integer', 'min:1000', 'max:'.now()->year],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'author.required' => 'The author field is required.',
            'genre.required' => 'The genre field is required.',
            'year_published.required' => 'The year published field is required.',
            'year_published.integer' => 'The year published must be a valid year (e.g. 1997).',
            'year_published.min' => 'The year published must be a 4-digit year no earlier than 1000.',
            'year_published.max' => 'The year published cannot be later than the current year ('.now()->year.').',
        ];
    }
}
