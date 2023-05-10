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

    public function tearDown(): void
    {
        if (file_exists($this->jsonDocsFile())) {
            unlink($this->jsonDocsFile());
        }

        if (file_exists($this->yamlDocsFile())) {
            unlink($this->yamlDocsFile());
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

        $generator = $app->make('url');

        if (method_exists($generator, 'forceRootUrl')) {
            $generator->forceRootUrl(env('APP_URL', 'http://localhost'));
        } else {
            $uri = $app->make('config')->get('app.url', 'http://localhost');

            $components = parse_url($uri);

            $server = $_SERVER;

            if (isset($components['path'])) {
                $server = array_merge($server, [
                    'SCRIPT_FILENAME' => $components['path'],
                    'SCRIPT_NAME' => $components['path'],
                ]);
            }

            $app->instance('request', \Illuminate\Http\Request::create(
                $uri, 'GET', [], [], [], $server
            ));
        }

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

        $app->router->group(['namespace' => 'SwaggerLume'], function ($route) {
            require __DIR__.'/../src/routes.php';
        });

        $this->copyAssets();

        return $app;
    }

    /**
     * @return bool
     */
    protected function isOpenApi()
    {
        return version_compare(config('swagger-lume.swagger_version'), '3.0', '>=');
    }

    protected function setPaths()
    {
        $cfg = config('swagger-lume');
        //Changing path
        $cfg['paths']['annotations'] = storage_path('annotations/Swagger');

        if ($this->isOpenApi()) {
            $cfg['paths']['annotations'] = storage_path('annotations/OpenApi');
        }

        //For test we want to regenerate always
        $cfg['generate_always'] = true;

        $cfg['generate_yaml_copy'] = true;

        //Adding constants which will be replaced in generated json file
        $cfg['constants']['SWAGGER_LUME_CONST_HOST'] = 'http://my-default-host.com';

        //Save the config
        config(['swagger-lume' => $cfg]);

        $cfg = config('view');
        $cfg['view'] = [
            'paths' => __DIR__.'/../resources/views',
            'compiled' => __DIR__.'/storage/logs',
        ];
        config($cfg);

        return $this;
    }

    protected function createJsonDocumentationFile()
    {
        file_put_contents($this->jsonDocsFile(), '');
    }

    protected function createYamlDocumentationFile()
    {
        file_put_contents($this->yamlDocsFile(), '');
    }

    protected function jsonDocsFile()
    {
        return sprintf(
            '%s/%s',
            config('swagger-lume.paths.docs'),
            config('swagger-lume.paths.docs_json')
        );
    }

    protected function yamlDocsFile()
    {
        return sprintf(
            '%s/%s',
            config('swagger-lume.paths.docs'),
            config('swagger-lume.paths.docs_yaml')
        );
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
