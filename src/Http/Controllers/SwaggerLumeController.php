<?php

namespace SwaggerLume\Http\Controllers;

use SwaggerLume\Generator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class SwaggerLumeController extends BaseController
{
    /**
     * Dump api-docs.json content endpoint.
     *
     * @param null $jsonFile
     *
     * @return \Illuminate\Http\Response
     */
    public function docs($jsonFile = null)
    {
        $filePath = config('swagger-lume.paths.docs').'/'.
            (! is_null($jsonFile) ? $jsonFile : config('swagger-lume.paths.docs_json'));

        if (! File::exists($filePath)) {
            abort(404, 'Cannot find '.$filePath);
        }

        $content = File::get($filePath);

        return new Response($content, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Display Swagger API page.
     *
     * @return \Illuminate\Http\Response
     */
    public function api()
    {
        if (config('swagger-lume.generate_always')) {
            Generator::generateDocs();
        }

        //need the / at the end to avoid CORS errors on Homestead systems.
        $response = new Response(
            view('swagger-lume::index', [
                'secure' => Request::secure(),
                'urlToDocs' => route('swagger-lume.docs'),
                'operationsSorter' => config('swagger-lume.operations_sort'),
                'configUrl' => config('swagger-lume.additional_config_url'),
                'validatorUrl' => config('swagger-lume.validator_url'),
            ]),
            200,
            ['Content-Type' => 'text/html']
        );

        return $response;
    }

    /**
     * Display Oauth2 callback pages.
     *
     * @return string
     */
    public function oauth2Callback()
    {
        return File::get(swagger_ui_dist_path('oauth2-redirect.html'));
    }
}
