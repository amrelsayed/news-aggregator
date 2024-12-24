<?php

namespace Tests\Unit\Services;

use App\Services\TheGuardianService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Factory as HttpClient;
use Mockery;
use Tests\TestCase;

class TheGuardianServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetches_and_transforms_articles_from_nytimes()
    {
        $mockApiResponse = [
            'status' => 'OK',
            'results' => [
                [
                    'sectionName' => 'World news',
                    'webPublicationDate' => '2022-10-21T14:06:14Z',
                    'webTitle' => 'Russia-Ukraine war latest: what we know on day 240 of the invasion',
                    'pillarName' => 'News',
                ],
            ],
        ];

        $mockHttpClient = Mockery::mock(HttpClient::class);
        $mockHttpClient->shouldReceive('get')
            ->once()
            ->with(config('services.guardian.url'), [
                'api-key' => config('services.guardian.key'),
            ])
            ->andReturn(new \Illuminate\Http\Client\Response(
                new \GuzzleHttp\Psr7\Response(200, [], json_encode($mockApiResponse))
            ));

        $this->app->instance(HttpClient::class, $mockHttpClient);

        $newsService = new TheGuardianService($mockHttpClient);
        $articles = $newsService->fetchArticles();

        $this->assertCount(1, $articles);
        $this->assertEquals('Russia-Ukraine war latest: what we know on day 240 of the invasion', $articles[0]['title']);
    }
}
