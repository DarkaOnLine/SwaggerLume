<?php

return [

    'api' => [
        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's title
        |--------------------------------------------------------------------------
         */
        'title' => 'WaveRFID API',

        /*
        |--------------------------------------------------------------------------
        | Auth token prefix, which will be appended to submitted auth_token/key
        |--------------------------------------------------------------------------
         */
        'auth_token_prefix' => env('API_AUTH_TOKEN_PREFIX', ''),

        /*
        |--------------------------------------------------------------------------
        | Edit to set the api's Auth token
        |--------------------------------------------------------------------------
         */
        'auth_token' => env('API_AUTH_TOKEN', false),

        /*
        |--------------------------------------------------------------------------
        | Edit to set the api key variable in interface
        |--------------------------------------------------------------------------
         */
        'key_var' => env('API_KEY_VAR', 'api_key'),

        /*
        |--------------------------------------------------------------------------
        | Edit to set where to inject api key (header, query)
        |--------------------------------------------------------------------------
         */
        'key_inject' => env('API_KEY_INJECT', 'query'),
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
        'annotations' => base_path(env('SWAGGER_ANNOTATION_CHECK_FOLDER',"vendor/waveRFID")),

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
        'excludes' => ['SwaggerLume'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Turn this off to remove swagger generation on production
    |--------------------------------------------------------------------------
     */
    'generate_always' => env('SWAGGER_GENERATE_ALWAYS', true),

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

];
