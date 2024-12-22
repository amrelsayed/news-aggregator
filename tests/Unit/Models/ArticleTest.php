<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_model_has_expected_fields(): void
    {
        $article = Article::factory()->create();

        $this->assertEquals([
            'title',
            'content',
            'category_id',
            'author_id',
            'source_id',
            'published_at',
            'updated_at',
            'created_at',
            'id',
        ], array_keys($article->toArray()));
    }
}
