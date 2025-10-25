<?php

namespace App\Services\SearchForms;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\SearchForms\Base\SearchFormBase;
use App\Services\SearchForms\Base\SearchFormInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class UserSearchFormService extends SearchFormBase implements SearchFormInterface
{
    public function search(array $validated, string $responseName = 'users', ?string $redirect = null, ?string $notFoundMessage = null) : JsonResponse|RedirectResponse
    {
        if(!array_key_exists('id', $validated))
            $validated['id'] = '';

        if(!array_key_exists('loadWith', $validated))
        {
            $users = User::where("email", 'like', "%{$validated['email']}%")->orWhere('ulid', $validated['id'])->get()->toArray();
        }
        else
        {
            $loadWithArr = explode(', ', $validated['loadWith']);
            $users = User::with($loadWithArr)->where("email", 'like', "%{$validated['email']}%")->orWhere('ulid', $validated['id'])->get()->toArray();
        }

        return parent::validate($users, $responseName, $redirect, $notFoundMessage);
    }
}
