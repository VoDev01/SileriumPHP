<?php

namespace App\Services;

use Illuminate\Http\Request;

class SearchFormKeyAuthService
{
    public static function AuthenticateKey(Request $request, string $queryModelSessionKeyName, string $searchKeySessionKeyName, int $perPage = 5)
    {
        if ($request->session()->get($queryModelSessionKeyName) != null)
        {
            if ($request->session()->get($searchKeySessionKeyName) != $request->searchKey)
            {
                $request->session()->forget($queryModelSessionKeyName);
                $paginated = null;
            }
            else
            {
                if (is_string($request->session()->get($queryModelSessionKeyName)))
                {
                    $json = json_decode($request->session()->get($queryModelSessionKeyName), true)[$queryModelSessionKeyName];
                    $paginated = ManualPaginatorService::paginate($json, $perPage, $request->page);
                }
                else
                    $paginated = ManualPaginatorService::paginate($request->session()->get($queryModelSessionKeyName), $perPage, $request->page);
            }
        }
        else
            $paginated = null;
        return $paginated;
    }
}
