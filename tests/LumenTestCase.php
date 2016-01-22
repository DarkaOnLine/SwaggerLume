<?php

class LumenTestCase extends Laravel\Lumen\Testing\TestCase
{
    public function tearDown()
    {
        if(file_exists($this->jsonDocsFile())) {
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
		$app = new Laravel\Lumen\Application(
		    realpath(__DIR__)
		);

        $app->withFacades();

        $app->configure('swagger-lume');


        $app->singleton(
            Illuminate\Contracts\Console\Kernel::class,
            ConsoleKernel::class
        );

		$app->register(\SwaggerLume\ServiceProvider::class);

		$app->group(['namespace' => 'SwaggerLume'], function ($app) {
		    require __DIR__.'/../src/routes.php';
		});
		return $app;
    }

    protected function setPaths()
    {
        $cfg = config('swagger-lume');
        $cfg['paths']['annotations'] = storage_path('annotations');
        $cfg['generate_always'] = true;
        config(['swagger-lume' => $cfg]);

        $cfg = config('view');
        $cfg['view'] = [
            'paths' => __DIR__.'/../resources/views',
            'compiled' => __DIR__.'/storage/logs'
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