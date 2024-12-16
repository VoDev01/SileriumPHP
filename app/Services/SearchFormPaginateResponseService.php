<?php

namespace App\Services;

use Illuminate\Http\Request;

class SearchFormPaginateResponseService
{
    public static function paginate(Request $request, string $queryModelSessionKeyName, int $perPage = 5)
    {
        if ($request->session()->get($queryModelSessionKeyName) != null)
        {
            if (is_string($request->session()->get($queryModelSessionKeyName)))
            {
                $json = json_decode($request->session()->get($queryModelSessionKeyName), true)[$queryModelSessionKeyName];
                $paginated = ManualPaginatorService::paginate($json, $perPage, $request->page);
            }
            else
                $paginated = ManualPaginatorService::paginate($request->session()->get($queryModelSessionKeyName), $perPage, $request->page);
        }
        else
        {
            $paginated = null;
        }
        return $paginated;
    }
}
