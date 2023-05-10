<?php

namespace SwaggerLume\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as RequestFacade;
use Laravel\Lumen\Routing\Controller as BaseController;
use SwaggerLume\Generator;

class SwaggerLumeController extends BaseController
{
    /**
     * Dump api-docs.json content endpoint.
     *
     * @param  null  $docsFile
     * @return \Illuminate\Http\Response
     */
    public function docs()
    {
        $filePath = sprintf(
            '%s/%s',
            config('swagger-lume.paths.docs'),
            config('swagger-lume.paths.format_to_use_for_docs') === 'json' ? config('swagger-lume.paths.docs_json') : config('swagger-lume.paths.docs_yaml')
        );

        $yaml = false;
        $parts = explode('.', $filePath);

        if (! empty($parts)) {
            $extension = array_pop($parts);
            $yaml = strtolower($extension) === 'yaml';
        }

        if (config('swagger-lume.generate_always') && ! File::exists($filePath)) {
            try {
                Generator::generateDocs();
            } catch (\Exception $e) {
                Log::error($e);

                abort(
                    404,
                    sprintf(
                        'Unable to generate documentation file to: "%s". Please make sure directory is writable. Error: %s',
                        $filePath,
                        $e->getMessage()
                    )
                );
            }
        }

        if (! File::exists($filePath)) {
            abort(404, 'Cannot find '.$filePath);
        }

        $content = File::get($filePath);

        if ($yaml) {
            return Response($content, 200, [
                'Content-Type' => 'application/yaml',
                'Content-Disposition' => 'inline',
                'filename' => config('swagger-lume.paths.docs_yaml'),
            ]);
        }

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
        return new Response(
            view('swagger-lume::index', [
                'secure' => RequestFacade::secure(),
                'urlToDocs' => route('swagger-lume.docs'),
                'operationsSorter' => config('swagger-lume.operations_sort'),
                'configUrl' => config('swagger-lume.additional_config_url'),
                'validatorUrl' => config('swagger-lume.validator_url'),
            ]),
            200,
            ['Content-Type' => 'text/html']
        );
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
