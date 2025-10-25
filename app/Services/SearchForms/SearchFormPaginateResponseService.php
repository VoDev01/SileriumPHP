<?php

namespace App\Services\SearchForms;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Actions\ManualPaginatorAction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * Paginates SearchForm response
 */
class SearchFormPaginateResponseService
{
    /**
     * Paginate found models stored in cache
     *
     * @param string $modelName Name of the model
     * @param int $page Current page
     * @param integer $perPage Max results per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate(string $modelName, int $page = 1, int $perPage = 5)
    {
        $paginated = null;
        strtolower(trim($modelName));
        if (Cache::has($modelName))
        {
            if(is_a(Cache::get($modelName), LengthAwarePaginator::class))
                return Cache::get($modelName);

            else if (is_string(Cache::get($modelName)))
            {
                $json = json_decode(Cache::get($modelName), true)[$modelName];
                $paginated = ManualPaginatorAction::paginate($json, $perPage, $page);
            }
            else
                $paginated = ManualPaginatorAction::paginate(Cache::get($modelName), $perPage, $page);
        }

        return $paginated;
    }

    /**
     * Paginate relations of the found models
     *
     * @param Request $request
     * @param string $modelName Name of the model
     * @param string $relationName Snake case name of the relation
     * @param int $page Current page
     * @param integer $perPage Max results per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginateRelations(string $modelName, string $relationName, int $page = 1, int $perPage = 5)
    {
        $paginated = null;
        strtolower(trim($modelName));
        if (Cache::has($modelName))
        {
            if (is_string(Cache::get($modelName)))
            {
                $json = json_decode(Cache::get($modelName), true);
                $relations = [];
                foreach ($json as $inst)
                {
                    if (array_key_exists($relationName, $inst))
                    {
                        foreach ($inst[$relationName] as $relation)
                        {
                            array_push($relations, $relation);
                        }
                    }
                }
                $paginated = ManualPaginatorAction::paginate($relations, $perPage, $page);
            }
            else
            {
                $data = Cache::get($modelName);
                $relations = [];
                foreach ($data as $inst)
                {
                    if (array_key_exists($relationName, $inst))
                    {
                        foreach ($inst[$relationName] as $relation)
                        {
                            array_push($relations, $relation);
                        }
                    }
                }
                $paginated = ManualPaginatorAction::paginate($relations, $perPage, $page);
            }
        }
        return $paginated;
    }
}
