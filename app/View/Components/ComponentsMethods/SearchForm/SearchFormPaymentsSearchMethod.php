<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use App\Models\Payment;

class SearchFormPaymentsSearchMethod
{
    public static function searchPayments(array $validated)
    {
        $loadWith = explode(', ', $validated['loadWith']);
        $payments = Payment::whereHas('order.user', function($query) use ($validated){
            $query->where('name', 'like', '%'.$validated['name'].'%')->where('surname', 'like', '%'.$validated['surname'].'%');
        })->with($loadWith)->get();
        if (key_exists('redirect', $validated))
            return redirect()->route($validated['redirect'])->with('payments', json_encode($payments));
        else
            return response()->json(json_encode($payments));
    }
}
