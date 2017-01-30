<?php

use Swagger\Swagger;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

$app->get(config('swagger-lume.routes.docs'), function ($page = 'api-docs.json') {
    $filePath = config('swagger-lume.paths.docs')."/{$page}";

    if (File::extension($filePath) === '') {
        $filePath .= '.json';
    }

    if (! File::exists($filePath)) {
        App::abort(404, "Cannot find {$filePath}");
    }

    $content = File::get($filePath);

    return new Response($content, 200, [
        'Content-Type' => 'application/json',
    ]);
});

$app->get(config('swagger-lume.routes.api'), function () {
    if (config('swagger-lume.generate_always')) {
        \SwaggerLume\Generator::generateDocs();
    }

    if (config('swagger-lume.proxy')) {
        $proxy = (new Request())->server('REMOTE_ADDR');
        (new Request())->setTrustedProxies([$proxy]);
    }

    $extras = [];
    $conf = config('swagger-lume');
    if (array_key_exists('validatorUrl', $conf)) {
        // This allows for a null value, since this has potentially
        // desirable side effects for swagger.  See the view for more
        // details.
        $extras['validatorUrl'] = $conf['validatorUrl'];
    }

    //need the / at the end to avoid CORS errors on Homestead systems.
    $response = new Response(
        view('swagger-lume::index', [
            'apiTitle'       => config('swagger-lume.api.title'),
            'secure'         => (new Request())->secure(),
            'urlToDocs'      => url(config('swagger-lume.routes.docs')),
            'requestHeaders' => config('swagger-lume.headers.request'),
            'docExpansion'       => config('swagger-lume.docExpansion'),
            'highlightThreshold' => config('swagger-lume.highlightThreshold'),
            'apisSorter' => config('swagger-lume.apisSorter'),
        ], $extras),
        200
    );

    if (is_array(config('swagger-lume.headers.view')) && ! empty(config('swagger-lume.headers.view'))) {
        foreach (config('swagger-lume.headers.view') as $key => $value) {
            $response->header($key, $value);
        }
    }

    return $response;
});
