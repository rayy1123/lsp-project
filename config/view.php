<?php

return [

    /*
    |--------------------------------------------------------------------------|
    | View Storage Path                                                        |
    |--------------------------------------------------------------------------|
    |
    | Most templating engines compile templates to PHP code for storage. Here
    | you may specify where compiled templates should be stored for your
    | application. Usually, this is within storage/framework.
    |
    */

    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views')) ?: storage_path('framework/views')
    ),

    /*
    |--------------------------------------------------------------------------|
    | Template Paths                                                           |
    |--------------------------------------------------------------------------|
    |
    | Most templating engines use a path to find the view files. Here you
    | may specify the paths where your views are located.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

];