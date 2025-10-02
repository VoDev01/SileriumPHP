<?php

namespace App\Actions;

class SetConfigAction
{
    public static function set(string $configName, string $setting, string $key, string $value)
    {
        $config = file_get_contents(config_path() . "/$configName.php");
        preg_match("/'$setting'\s+=>\s+\[(?<options>\X*)\]/U", $config, $matches);

        $matches['options'] = preg_replace(
            "/['\"]" . $key . "['\"]\s+=>\s+['\"].*['\"]/",
            "'$key' => '$value'",
            $matches['options']
        );

        $newOptions = "'$setting' => [{$matches['options']}]";
        $config = preg_replace("/'$setting'\s+=>\s+\[\X*\]/U", $newOptions, $config);

        file_put_contents(config_path() . "/$configName.php", $config);
    }
}