<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase;
use SwaggerLume\ServiceProvider as SwaggerLumeServiceProvider;

class LumenTestCase extends TestCase
{
    public $auth_token_prefix = 'TEST_PREFIX_';

    public $auth_token = 'N3W_T0K3N';

    public $key_var = 'TEST_KEY';

    public $docs_url = 'http://localhost/docs';

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
            ExceptionHandler::class,
            ExceptionsHandler::class
        );

        $app->singleton(
            Kernel::class,
            ConsoleKernel::class
        );

        $app->register(SwaggerLumeServiceProvider::class);

        $app->group(['namespace' => 'SwaggerLume'], function ($route) {
            require __DIR__.'/../src/routes.php';
        });

        $this->copyAssets();

        return $app;
    }

    protected function setPaths()
    {
        $cfg = config('swagger-lume');
        //Changing path
        $cfg['paths']['annotations'] = storage_path('annotations');

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

    protected function copyAssets()
    {
        $src = __DIR__.'/../vendor/swagger-api/swagger-ui/dist/';
        $destination = __DIR__.'/vendor/swagger-api/swagger-ui/dist/';
        if (! is_dir($destination)) {
            $base = realpath(
                __DIR__.'/vendor'
            );


            mkdir($base = $base.'/swagger-api');
            mkdir($base = $base.'/swagger-ui');
            mkdir($base = $base.'/dist');
        }

        foreach (scandir($src) as $file) {
            $filePath = $src.$file;

            if (! is_readable($filePath) || is_dir($filePath)) {
                continue;
            }

            copy(
                $filePath,
                $destination.$file
            );
        }
    }
}
