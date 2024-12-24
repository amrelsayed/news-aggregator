<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Carbon;

class TheGuardianService implements NewsApiServiceInterface
{
    use NewsAPITrait;

    public function __construct(private HttpClient $http) {}

    public function fetchArticles(): array
    {
        $response = $this->http->get(config('services.guardian.url'), [
            'api-key' => config('services.guardian.key'),
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
                'title' => $article['webTitle'],
                'content' => $article['webTitle'],
                'published_at' => Carbon::parse($article['webPublicationDate']),
                'category_id' => $this->resolveCategoryId($article['sectionName'] ?? 'general'),
                'author_id' => $this->resolveAuthorId('The Guardian'),
                'source_id' => $this->resolveSourceId('The Guardian'),
            ];
        }, $articles);
    }
}
