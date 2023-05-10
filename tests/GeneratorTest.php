<?php

namespace Tests;

use SwaggerLume\Generator;

class GeneratorTest extends LumenTestCase
{
    /** @test */
    public function canGenerateApiJsonAndYamlFile()
    {
        $this->setPaths();

        Generator::generateDocs();

        $this->assertTrue(file_exists($this->jsonDocsFile()));
        $this->assertTrue(file_exists($this->yamlDocsFile()));

        $response = $this->get(config('swagger-lume.routes.docs'));

        $this->assertResponseOk();

        $this->assertStringContainsString('SwaggerLume', $response->response->getContent());
        $this->assertStringContainsString('my-default-host.com', $response->response->getContent());
    }

    /** @test */
    public function canGenerateApiJsonAndYamlFileWithChangedBasePath()
    {
        if ($this->isOpenApi() == true) {
            $this->markTestSkipped('only for openApi 2.0');
        }

        $this->setPaths();

        $cfg = config('swagger-lume');
        $cfg['paths']['base'] = '/new_path/is/here';
        config(['swagger-lume' => $cfg]);

        Generator::generateDocs();

        $this->assertTrue(file_exists($this->jsonDocsFile()));
        $this->assertTrue(file_exists($this->yamlDocsFile()));

        $response = $this->get(config('swagger-lume.routes.docs'));

        $this->assertResponseOk();

        $this->assertStringContainsString('SwaggerLume', $response->response->getContent());
        $this->assertStringContainsString('new_path', $response->response->getContent());
    }

    /** @test */
    public function canSetProxy()
    {
        $this->setPaths();

        $cfg = config('swagger-lume');
        $cfg['proxy'] = 'http://proxy.dev';
        config(['swagger-lume' => $cfg]);

        $this->get(config('swagger-lume.routes.api'));

        $this->assertResponseOk();

        $this->assertTrue(file_exists($this->jsonDocsFile()));
    }

    /** @test */
    public function canSetValidatorUrl()
    {
        $this->setPaths();

        $cfg = config('swagger-lume');
        $cfg['validator_url'] = 'http://validator-url.dev';
        config(['swagger-lume' => $cfg]);

        $response = $this->get(config('swagger-lume.routes.api'));

        $this->assertResponseOk();

        $this->assertStringContainsString('validator-url.dev', $response->response->getContent());

        $this->assertTrue(file_exists($this->jsonDocsFile()));
    }
}
