<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Carbon;

class NewsAPIService implements NewsApiServiceInterface
{
    use NewsAPITrait;

    public function __construct(private HttpClient $http) {}

    public function fetchArticles(): array
    {
        $response = $this->http->get(config('services.newsapi.url'), [
            'apiKey' => config('services.newsapi.key'),
            'country' => 'us',
            'category' => 'technology',
        ]);

        $data = $response->json();

        if (isset($data['articles'])) {
            return $this->transformArticles($data['articles']);
        }

        return [];
    }

    private function transformArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'content' => $article['content'] ?? $article['description'],
                'published_at' => Carbon::parse($article['publishedAt']),
                'category_id' => $this->resolveCategoryId($article['category'] ?? 'general'),
                'author_id' => $this->resolveAuthorId($article['author']),
                'source_id' => $this->resolveSourceId($article['source']['name']),
            ];
        }, $articles);
    }
}
