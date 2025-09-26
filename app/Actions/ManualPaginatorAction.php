<?php

namespace App\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Paginator that simplifies array pagination
 */
class ManualPaginatorAction
{
    /**
     * @param array $items
     * @param integer $perPage Max items per page
     * @param integer|null $page Current page
     * @param array $options Query, fragment, pageName, path is resolved to the current
     * @return LengthAwarePaginator
     */
    public static function paginate(array $items, int $perPage = 5, ?int $page = null, $options = []): LengthAwarePaginator
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        return new LengthAwarePaginator(array_slice($items, ($page - 1) * $perPage, $perPage), count($items), $perPage, $page, array_merge(['path' => Paginator::resolveCurrentPath()], $options));
    }
}
