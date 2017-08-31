<?php

$route->get(config('swagger-lume.routes.docs'), [
    'as' => 'swagger-lume.docs',
    'middleware' => config('swagger-lume.routes.middleware.docs', []),
    'uses' => 'Http\Controllers\SwaggerLumeController@docs',
]);

$route->get(config('swagger-lume.routes.api'), [
    'as' => 'swagger-lume.api',
    'middleware' => config('swagger-lume.routes.middleware.api', []),
    'uses' => 'Http\Controllers\SwaggerLumeController@api',
]);

$route->get(config('swagger-lume.routes.assets').'/{asset}', [
    'as' => 'swagger-lume.asset',
    'middleware' => config('swagger-lume.routes.middleware.asset', []),
    'uses' => 'Http\Controllers\SwaggerLumeAssetController@index',
]);

$route->get(config('swagger-lume.routes.oauth2_callback'), [
    'as' => 'swagger-lume.oauth2_callback',
    'middleware' => config('swagger-lume.routes.middleware.oauth2_callback', []),
    'uses' => 'Http\Controllers\SwaggerLumeController@oauth2Callback',
]);
