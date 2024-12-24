<?php

namespace App\Actions\Article;

use App\Models\Article;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ListArticlesAction
{
    /**
     * Get list of articles with filters
     *
     * @param  array  $filters  ['keyword', 'from_date', 'to_date', 'category_id', 'author_id', 'source_id']
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function excute(array $filters): LengthAwarePaginator
    {
        $cacheKey = $this->getCacheKey($filters);

        $products = Cache::remember($cacheKey, 60 * 60, function () use ($filters) {
            $query = Article::query()
                ->when($filters['keyword'] ?? null, fn ($query, $keyword) => $query->where('title', 'like', "%$keyword%"))
                ->when($filters['from_date'] ?? null, fn ($query, $date) => $query->whereDate('published_at', '>=', $date))
                ->when($filters['to_date'] ?? null, fn ($query, $date) => $query->whereDate('published_at', '<=', $date))
                ->when($filters['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
                ->when($filters['author_id'] ?? null, fn ($query, $authorId) => $query->where('author_id', $authorId))
                ->when($filters['source_id'] ?? null, fn ($query, $sourceId) => $query->where('source_id', $sourceId));

            $query->with(['author', 'category', 'source']);

            return $query->paginate(10);
        });

        return $products;
    }

    private function getCacheKey(array $filters): string
    {
        $key = 'articles:';

        ksort($filters);

        $query_str = http_build_query($filters);

        return $key.$query_str;
    }
}
