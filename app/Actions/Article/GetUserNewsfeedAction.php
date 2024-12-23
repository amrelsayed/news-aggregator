<?php

namespace App\Actions\Article;

use App\Actions\Preference\GetUserPreferencesAction;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetUserNewsfeedAction
{
    public function __construct(private GetUserPreferencesAction $getUserPreferencesAction)
    {
        //
    }

    /**
     * Get user customized newseed
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function execute(): LengthAwarePaginator|array
    {
        $preferences = $this->getUserPreferencesAction->execute();

        if ($preferences->isEmpty()) {
            return [];
        }

        $typeIds = $this->getTypesIds($preferences);

        $articlesQuery = Article::query();

        foreach ($typeIds as $column => $ids) {
            $articlesQuery->orWhereIn($column, $ids);
        }

        return $articlesQuery->with(['category', 'author', 'source'])
            ->orderBy('published_at', 'desc')
            ->paginate(10);
    }

    private function getTypesIds(Collection $preferences): array
    {
        $typeIds = [];

        foreach ($preferences as $preference) {
            $column = $this->getForeignKey($preference->preferencable_type) ?? null;
            $typeIds[$column][] = $preference->preferencable_id;
        }

        return $typeIds;
    }

    private function getForeignKey(string $type): string
    {
        $maping = [
            Category::class => 'category_id',
            Author::class => 'author_id',
            Source::class => 'source_id',
        ];

        return $maping[$type];
    }
}
