<?php

namespace Tests\Feature\Services\SearchForm;

use App\Actions\ManualPaginatorAction;
use App\Models\Role;
use App\Models\User;
use App\Services\SearchForms\SearchFormPaginateResponseService;
use App\Services\SearchForms\UserSearchFormService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SearchFormPaginateResponseServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPaginate()
    {
        $user = User::factory(30)->hasAttached(Role::factory()->create(['role' => 'user']), relationship: 'roles')->create()->first();

        $response = (new UserSearchFormService)->search([
            'email' => $user->email
        ]);        
        
        $response = json_decode($response->content(), true);

        $this->assertTrue($response['users'][0]['ulid'] === $user->ulid);
        $this->assertTrue(Cache::has('users'));

        $paginated = SearchFormPaginateResponseService::paginate('users', 1, 15);

        $this->assertTrue($paginated->items() === ManualPaginatorAction::paginate(Cache::get('users'), 15, 1)->items());

        Cache::clear();

        //Test validate

        $response = (new UserSearchFormService)->search([
            'email' => 'jjsghu@gamail.com'
        ], notFoundMessage: 'Не найдено');

        $this->assertTrue(json_decode($response->content(), true)['users'] === 'Не найдено' && $response->getStatusCode() === 404);
    }


    public function testPaginateRelations()
    {
        $user = User::factory()->hasAttached(Role::factory(30)->create(['role' => 'user']), relationship: 'roles')->create()->first();

        $response = (new UserSearchFormService)->search([
            'email' => $user->email,
            'loadWith' => 'roles'
        ]);

        $response = json_decode($response->content(), true);

        $this->assertTrue($response['users'][0]['ulid'] === $user->ulid);
        $this->assertTrue(Cache::has('users'));

        $paginated = SearchFormPaginateResponseService::paginateRelations('users', 'roles', 1, 15);

        $this->assertTrue($paginated->items() === ManualPaginatorAction::paginate($user->roles->toArray(), 15, 1)->items());

        Cache::clear();

        //Test validate

        $response = (new UserSearchFormService)->search([
            'email' => 'jjsghu@gamail.com'
        ], notFoundMessage: 'Не найдено');

        $this->assertTrue(json_decode($response->content(), true)['users'] === 'Не найдено' && $response->getStatusCode() === 404);
    }
}
