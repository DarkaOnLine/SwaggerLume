<?php

use Swagger\Swagger;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

$this->app->get(config('swagger-lume.routes.docs'), function ($page = 'api-docs.json') {
    $filePath = config('swagger-lume.paths.docs') . "/{$page}";

    if (File::extension($filePath) === "") {
        $filePath .= ".json";
    }
    if (!File::Exists($filePath)) {
        App::abort(404, "Cannot find {$filePath}");
    }

    $content = File::get($filePath);
    return (new Response($content, 200, array(
        'Content-Type' => 'application/json'
    )));
});

$this->app->get(config('swagger-lume.routes.api'), function () {
    if (config('swagger-lume.generate_always')) {
        \SwaggerLume\Generator::generateDocs();
    }

    if (config('swagger-lume.proxy')) {
        $proxy = (new Request)->server('REMOTE_ADDR');
        (new Request)->setTrustedProxies(array($proxy));
    }

    //need the / at the end to avoid CORS errors on Homestead systems.
    $response = new Response(
        view('swagger-lume::index', array(
            'apiKey' => config('swagger-lume.api.auth_token'),
            'apiKeyVar' => config('swagger-lume.api.key_var'),
            'apiKeyInject' => config('swagger-lume.api.key_inject'),
            'secure' => (new Request)->secure(),
            'urlToDocs' => url(config('swagger-lume.routes.docs')),
            'requestHeaders' => config('swagger-lume.headers.request')
        )),
        200
    );

    if (is_array(config('swagger-lume.headers.view')) && !empty(config('swagger-lume.headers.view'))) {
        foreach (config('swagger-lume.headers.view') as $key => $value) {
            $response->header($key, $value);
        }
    }

    return $response;
});