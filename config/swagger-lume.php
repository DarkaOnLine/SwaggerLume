<?php

return [

    'api' => [
        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's title
        |--------------------------------------------------------------------------
         */
        'title' => 'Swagger Lume API',

        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's version number
        |--------------------------------------------------------------------------
         */
        'version' => env('DEFAULT_API_VERSION', '1'),
    ],

    'routes' => [
        /*
        |--------------------------------------------------------------------------
        | Route for accessing api documentation interface
        |--------------------------------------------------------------------------
         */
        'api' => 'api/documentation',
        /*
        |--------------------------------------------------------------------------
        | Route for accessing parsed swagger annotations.
        |--------------------------------------------------------------------------
         */
        'docs' => 'docs',
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Absolute path to location where parsed swagger annotations will be stored
        |--------------------------------------------------------------------------
         */
        'docs' => storage_path('api-docs'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory containing the swagger annotations are stored.
        |--------------------------------------------------------------------------
         */
        'annotations' => base_path('app'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory where to export assets
        |--------------------------------------------------------------------------
         */
        'assets' => base_path('public/vendor/swagger-lume'),

        /*
        |--------------------------------------------------------------------------
        | Path to assets public directory
        |--------------------------------------------------------------------------
         */
        'assets_public' => '/vendor/swagger-lume',

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directory where to export views
        |--------------------------------------------------------------------------
         */
        'views' => base_path('resources/views/vendor/swagger-lume'),

        /*
        |--------------------------------------------------------------------------
        | Absolute path to directories that you would like to exclude from swagger generation
        |--------------------------------------------------------------------------
         */
        'excludes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Turn this off to remove swagger generation on production
    |--------------------------------------------------------------------------
     */
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', false),

    /*
    |--------------------------------------------------------------------------
    | Edit to set the swagger version number
    |--------------------------------------------------------------------------
     */
    'swagger_version' => env('SWAGGER_VERSION', '2.0'),

    /*
    |--------------------------------------------------------------------------
    | Edit to trust the proxy's ip address - needed for AWS Load Balancer
    |--------------------------------------------------------------------------
     */
    'proxy' => false,

    /*
    |--------------------------------------------------------------------------
    | Edit to change layout of GUI ( 'none', 'list' or 'full')
    |--------------------------------------------------------------------------
    */
    'docExpansion' => env('SWAGGER_DOC_EXPANSION', 'none'),

    /*
    |--------------------------------------------------------------------------
    | Edit to change the maximum number of characters to highlight code.
    |--------------------------------------------------------------------------
    */
    'highlightThreshold' => env('SWAGGER_HIGHLIGHT_THRESHOLD', 5000),

    /*
    |--------------------------------------------------------------------------
    | Edit to change the maximum number of characters to highlight code.
    |--------------------------------------------------------------------------
    */
    'apisSorter' => env('SWAGGER_API_SORTER', 'alpha'),

    /*
     |--------------------------------------------------------------------------
     | Uncomment to pass the validatorUrl parameter to SwaggerUi init on the JS
     | side.  A null value here disables validation.  A string will override
     | the default url.  If not specified, behavior is default and validation
     | is enabled.
     |--------------------------------------------------------------------------
     */
    // 'validatorUrl' => null,

    'headers' => [
        /*
        |--------------------------------------------------------------------------
        | Uncomment to add response headers when swagger is generated
        |--------------------------------------------------------------------------
         */
        /*"view" => [
        'Content-Type' => 'text/plain'
        ],*/
        /*
        |--------------------------------------------------------------------------
        | Uncomment to add request headers when swagger performs requests
        |--------------------------------------------------------------------------
         */
        /*"request" => [
    'TestMe' => 'testValue'
    ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Uncomment to add constants which can be used in anotations
    |--------------------------------------------------------------------------
     */
    'constants' => [
        //'SWAGGER_LUME_CONST_HOST' => env('SWAGGER_LUME_CONST_HOST', 'http://my-default-host.com'),
    ],

];
