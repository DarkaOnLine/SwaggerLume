<?php

namespace SwaggerLume\Console\Helpers;

use Illuminate\Support\Facades\Artisan;

class CommandsTest extends \LumenTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app->prepareForConsoleCommand();
        $this->setPaths();
    }

    /** @test */
    public function can_generate_json_documentation()
    {
        Artisan::call('swagger-lume:generate');

        $this->assertFileExists($this->jsonDocsFile());

        $fileContent = file_get_contents($this->jsonDocsFile());

        $this->assertJson($fileContent);
        $this->assertContains('Swagger Lume API', $fileContent);
    }

    /** @test */
    public function can_publish_assets()
    {
        $this->setPaths();
        Artisan::call('swagger-lume:publish');
    }
}

function copy()
{
    return true;
}

function chmod()
{
    return true;
}

function mkdir()
{
    return true;
}
