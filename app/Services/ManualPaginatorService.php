<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class ManualPaginatorService
{
    public static function paginate(array $items, int $perPage = 5, ?int $page = null, $options = []) : LengthAwarePaginator
    {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = collect($items);
        return new LengthAwarePaginator($items, $items->count(), $perPage, $page, $options);
    }
}