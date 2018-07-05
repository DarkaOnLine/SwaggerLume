<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;

class CommandsTest extends LumenTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->app->prepareForConsoleCommand();
        $this->setPaths();
    }

    /** @test */
    public function canGenerateJsonDocumentation()
    {
        Artisan::call('swagger-lume:generate');

        $this->assertFileExists($this->jsonDocsFile());

        $fileContent = file_get_contents($this->jsonDocsFile());

        $this->assertJson($fileContent);
        $this->assertContains('Swagger Lume API', $fileContent);

        //Check if constants are replaced
        $this->assertContains('http://my-default-host.com', $fileContent);
        $this->assertNotContains('SWAGGER_LUME_CONST_HOST', $fileContent);
    }

    /** @test */
    public function canPublishAssets()
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
