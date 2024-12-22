<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = Category::factory(5)->create();
        $authors = Author::factory(5)->create();
        $sources = Source::factory(5)->create();

        for ($i = 0; $i < 10; $i++) {
            Article::factory(10)->create([
                'category_id' => $categories->random()->id,
                'author_id' => $authors->random()->id,
                'source_id' => $sources->random()->id,
            ]);
        }
    }
}
