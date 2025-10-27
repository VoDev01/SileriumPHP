<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path(env('APP_MEDIA_PATH')),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path(),
            'visibility' => 'public',
            'throw' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

        'dropbox' => [
            'driver' => 'dropbox',
            'url' => env('DROPBOX_CONTENT_URI'),
            'throw' => false,
            'access_token' => 'sl.u.AGCOAD1pPeb-Vh_mixgPXlJh_MDSwHL3f-GccjYaGTO05v_dC0OF3q4XZRD2cIRt2x05Psp0NkpsMGvTfexlDxe3ZLJ_sXCDL7KG19tRxsaffqjVyrdzRNoYrWvJcxQwr86De6BLTqQxRgXJPFDJC9TsF98kbvsIxdgoKXtA3kd5Ud_VBTGIq88-RnVyUGyoq4hMItCGnwGx5SHm2-WpfBSOkZ2VwdL_HTtRLiVDEXYv6nRLaKdd2PKBYnYDCq3Tqd85VYVL0XG8U_kLkHR5PK5SEwr0ro_G95FitQuvA3FcbXz9tHbMVF1YmbsdK1tKu2TEz8iF3-6POE4MhSJNHnmJsO-NwV8kH9e8Wa3gOvH25nFK0KGG2YvHgUAilIXl7ZcxliGIu645vzJUNiATAR22_xjHmplK2AWz0lXpOhZtEPKSqchYE8gmz88oxGlm-3DPwSMATGHmDBjHJmE9NagSTCQxj8BvAILh1ECEVV3XCY_jqAM4stZ4JH5HBw8YCjjP31GMUjdR9A59GDfcrJixZKhZ2rqecuJBnP0wTY-Ao7FsRNjY5pyZqwX47sL_fUCqvY5qfbpm3pE28lypUIwqZXiwxfoYM3ARtoXcGGfc9H0rHZKRe4VYXnJ05ExFpT9QCYkkYhbisqeFo3tDEX6_ua5IJ07N3UGjwDD4jYvSFkFMXeiHJc5bW9WvEhNaSMY-P02mMaTSZeeKn6ZNtUHqVJhLd6PpoSikPUttVuHsSnQcWDj-KS-BkaqhJqSEKbO8zipzu5deFuGTN5sr8HYEZzyujftgJpr0uur3zE7TwaSacnslcrO6yyPwsgGLiLRcnRzWrtDQoSYxWEwivuoCLFXUpm49Ivzz_xIlwpHmmIuGHb6uD6SZb83lv-W_Rr-VAEmu-JEz3-aqUOvTks7F3i_6KFz8FDCbMek4vffcTr4LzIeVK8U35-9O3OzlDGntZmkuF6YCaQ2m1xfDRvlrvZPLaC64jhVNl0G3LTADXNRWP_GAj2h8Er_fZ1ZRgnOaDiozYuMhf0brqvNQoxP_ZunKR6i9pGnH_bdEXRIT0kRkA5dAFZcLrQIcKnF6U02Vt-zRiU9pTUh9g9iftITXRRflIVfUrOfXhImWHQZ7JyiMF_hhs7cbS67pPb_EkKSFP0IywTPkmeoJ9m1QNwN0xnn1vlWTFs-RpbEXYFvRLg7Hb7PDjmAJeEz3xGj8Q14M-PtsoB0THalOaRvPOpoMNliyrsCSvYBXmTKmi4Gx6EJgVeMUCJDXUAe_Pgpo1ErLhJjn3uxNbxf2uNCqZK-1tMV8DibQo2h0bIhvqO5jzzuns-z2tvatQfnNeZlBzcPpolRtFY27suwAii2RpN6R7QKMiHwDfP-3vkI7JGrsDvV2sfss_srXXNUlvptV8MnMI7lmgcJNXGalhorB3rhF',
            'access_token_expires_in' => '02-10-2025 20:01:33'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
    ],

];
