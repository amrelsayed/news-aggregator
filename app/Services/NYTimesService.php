<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Carbon;

class NYTimesService implements NewsApiServiceInterface
{
    use NewsAPITrait;

    public function __construct(private HttpClient $http) {}

    public function fetchArticles(): array
    {
        $response = $this->http->get(config('services.nytimes.url'), [
            'api-key' => config('services.nytimes.key'),
        ]);

        $data = $response->json();

        if (isset($data['results'])) {
            return $this->transformArticles($data['results']);
        }

        return [];
    }

    private function transformArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'],
                'content' => $article['abstract'],
                'published_at' => Carbon::parse($article['published_date']),
                'category_id' => $this->resolveCategoryId($article['section'] ?? 'general'),
                'author_id' => $this->resolveAuthorId($article['byline'] ?? 'unkown'),
                'source_id' => $this->resolveSourceId('NYTimes'),
            ];
        }, $articles);
    }
}
