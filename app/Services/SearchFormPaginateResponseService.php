<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
    public static function paginateRelations(Request $request, string $queryModelSessionKeyName, string $relationName, int $perPage = 5)
    {
        if ($request->session()->get($queryModelSessionKeyName) != null)
        {
            if (is_string($request->session()->get($queryModelSessionKeyName)))
            {
                $json = json_decode($request->session()->get($queryModelSessionKeyName), true)[$queryModelSessionKeyName];
                $relations = new Collection();
                foreach($json as $inst)
                {
                    if(array_key_exists($relationName, $inst))
                    {
                        foreach($inst[$relationName] as $relation)
                        {
                            $relations->push($relation);
                        }
                    }
                }
                $paginated = ManualPaginatorService::paginate($relations->toArray(), $perPage, $request->page);
            }
            else
            {
                $requestArr = $request->session()->get($queryModelSessionKeyName);
                $relations = new Collection();
                foreach($requestArr as $inst)
                {
                    if(array_key_exists($relationName, $inst))
                    {
                        foreach($inst[$relationName] as $relation)
                        {
                            $relations->push($relation);
                        }
                    }
                }
                $paginated = ManualPaginatorService::paginate($relations->toArray(), $perPage, $request->page);
            }
        }
        else
        {
            $paginated = null;
        }
        return $paginated;
    }
}
