<?php

namespace App\View\Components\ComponentsMethods\SearchForm;

use App\Models\Payment;
use Illuminate\Http\Request;

class SearchFormPaymentsSearchMethod implements SearchFormInterface
{
    public static function search(Request $request, array $validated)
    {
        $loadWith = explode(', ', $validated['loadWith']);
        $payments = Payment::whereHas('order.user', function ($query) use ($validated)
        {
            $query->where('name', 'like', '%' . $validated['name'] . '%')->where('surname', 'like', '%' . $validated['surname'] . '%');
        })->with($loadWith)->get();
        if (key_exists('redirect', $validated))
        {
            if ($request->session()->get('payments') !== null)
                $request->session()->forget('payments');
            $request->session()->put('payments', json_encode($payments));
            return redirect()->route($validated['redirect']);
        }
        else
            return response()->json(json_encode($payments));
    }
}
