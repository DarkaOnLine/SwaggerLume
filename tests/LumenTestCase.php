<?php

namespace Tests;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase;
use SwaggerLume\ServiceProvider as SwaggerLumeServiceProvider;

class LumenTestCase extends TestCase
{
    public $auth_token_prefix = 'TEST_PREFIX_';

    public $auth_token = 'N3W_T0K3N';

    public $key_var = 'TEST_KEY';

    public $validator_url = 'http://validate.dev';

    public function tearDown()
    {
        if (file_exists($this->jsonDocsFile())) {
            unlink($this->jsonDocsFile());
        }
        parent::tearDown();
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        $app = new Application(
            realpath(__DIR__)
        );

        $app->withFacades();

        $app->configure('swagger-lume');

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            ExceptionsHandler::class
        );

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            ConsoleKernel::class
        );

        $app->register(SwaggerLumeServiceProvider::class);

        $app->group(['namespace' => 'SwaggerLume'], function ($app) {
            require __DIR__.'/../src/routes.php';
        });

        return $app;
    }

    protected function setPaths()
    {
        $cfg = config('swagger-lume');
        //Changing path
        $cfg['paths']['annotations'] = storage_path('annotations');

        //Changing api auth params
        $cfg['api']['auth_token_prefix'] = $this->auth_token_prefix;
        $cfg['api']['auth_token'] = $this->auth_token;
        $cfg['api']['key_var'] = $this->key_var;

        //Changing validation url
        $cfg['validatorUrl'] = $this->validator_url;

        //For test we want to regenerate always
        $cfg['generate_always'] = true;

        //Adding constants which will be replaced in generated json file
        $cfg['constants']['SWAGGER_LUME_CONST_HOST'] = 'http://my-default-host.com';

        //Save the config
        config(['swagger-lume' => $cfg]);

        $cfg = config('view');
        $cfg['view'] = [
            'paths'    => __DIR__.'/../resources/views',
            'compiled' => __DIR__.'/storage/logs',
        ];
        config($cfg);

        return $this;
    }

    protected function crateJsonDocumentationFile()
    {
        file_put_contents($this->jsonDocsFile(), '');
    }

    protected function jsonDocsFile()
    {
        return config('swagger-lume.paths.docs').'/api-docs.json';
    }
}
