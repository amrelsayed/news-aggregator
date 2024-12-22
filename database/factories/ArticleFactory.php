<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(5, true),
            'content' => fake()->paragraphs(3, true),
            'category_id' => Category::factory(),
            'author_id' => Author::factory(),
            'source_id' => Source::factory(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
