<?php

namespace App\Services;

use Illuminate\Http\Request;

class UpdateSessionValueJson
{
    public static function update(Request $request, string $key, $newVal, string $evalKey = 'id')
    {
        $sessionVal = json_decode($request->session()->get($key), true)[$key];
        foreach ($sessionVal as $i => $oldVal)
            if ($oldVal[$evalKey] == $newVal[$evalKey])
                $sessionVal[$i] = $newVal;
        $request->session()->put([$key => json_encode([$key => $sessionVal])]);
    }
    public static function delete(Request $request, string $key, $deletedVal, string $evalKey = 'id')
    {
        $sessionVal = json_decode($request->session()->get($key), true)[$key];
        foreach ($sessionVal as $i => $oldVal)
            if ($oldVal[$evalKey] == $deletedVal)
               $sessionVal[$i] = null;
        $request->session()->put([$key => json_encode([$key => $sessionVal])]);
    }
}