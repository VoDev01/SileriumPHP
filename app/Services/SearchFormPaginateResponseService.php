<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Actions\ManualPaginatorAction;

/**
 * Paginates SearchForm response
 */
class SearchFormPaginateResponseService
{
    /**
     * Paginate found models that is stored in session
     *
     * @param Request $request
     * @param string $queryModelSessionKeyName Snake case name of the model in session
     * @param integer $perPage Max results per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate(Request $request, string $queryModelSessionKeyName, int $perPage = 5)
    {
        if ($request->session()->get($queryModelSessionKeyName) != null)
        {
            if (is_string($request->session()->get($queryModelSessionKeyName)))
            {
                $json = json_decode($request->session()->get($queryModelSessionKeyName), true)[$queryModelSessionKeyName];
                $paginated = ManualPaginatorAction::paginate($json, $perPage, $request->page);
            }
            else
                $paginated = ManualPaginatorAction::paginate($request->session()->get($queryModelSessionKeyName), $perPage, $request->page);
        }
        else
        {
            $paginated = null;
        }
        return $paginated;
    }

    /**
     * Paginate relations of the found models
     *
     * @param Request $request
     * @param string $queryModelSessionKeyName Snake case name of the model in session
     * @param string $relationName Snake case name of the relation
     * @param integer $perPage Max results per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
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
                $paginated = ManualPaginatorAction::paginate($relations->toArray(), $perPage, $request->page);
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
                $paginated = ManualPaginatorAction::paginate($relations->toArray(), $perPage, $request->page);
            }
        }
        else
        {
            $paginated = null;
        }
        return $paginated;
    }
}
