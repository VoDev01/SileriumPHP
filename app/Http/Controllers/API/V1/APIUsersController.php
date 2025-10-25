<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Users\APIUserSearchRequest;
use App\Models\User;
use App\Services\SearchForms\UserSearchFormService;
use Illuminate\Http\Request;


class APIUsersController extends Controller
{
    public function search(APIUserSearchRequest $request)
    {
        $validated = $request->validated();

        return (new UserSearchFormService)->search($validated);
    }
}
