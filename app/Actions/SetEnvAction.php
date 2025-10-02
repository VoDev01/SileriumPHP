<?php

namespace App\Actions;

use Illuminate\Support\Facades\App;

class SetEnvAction
{
    public static function set(string $key, string $value)
    {
        file_put_contents(
            App::basePath() . '/.env',
            preg_replace(
                "/$key=\"{0,1}.*\"{0,1}/",
                "$key=\"$value\"",
                file_get_contents(App::basePath() . '/.env')
            )
        );
    }
}