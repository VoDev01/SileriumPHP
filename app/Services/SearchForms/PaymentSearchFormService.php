<?php

namespace App\Services\SearchForms;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Services\SearchForms\Base\SearchFormBase;
use App\Services\SearchForms\Base\SearchFormInterface;
use Illuminate\Support\Facades\DB;

class PaymentSearchFormService extends SearchFormBase implements SearchFormInterface
{
    public function search(array $validated, string $responseName = 'payments', ?string $redirect = null, ?string $notFoundMessage = null): JsonResponse|RedirectResponse
    {
        $loadWith = array_key_exists('loadWith', $validated) ? explode(', ', $validated['loadWith']) : '';

        $payments = DB::select('SELECT p.*,
        u.ulid as user_ulid,
        u.email, 
        u.name,
        u.surname,
        o.totalPrice,
        o.address,
        o.status as order_status,
        o.address FROM payments as p 
        INNER JOIN orders as o ON p.order_id = o.ulid
        INNER JOIN users as u ON o.user_id = u.id
        WHERE u.ulid LIKE ?', [
            $validated['id']
        ]);

        return parent::validate($payments, $responseName, $redirect, $notFoundMessage);
    }
}
