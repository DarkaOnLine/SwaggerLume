<?php

namespace Tests;

use SwaggerLume\Generator;

class SecurityDefinitionsTest extends LumenTestCase
{
    /** @test */
    public function canGenerateApiJsonFileWithSecurityDefinition()
    {
        if ($this->isOpenApi()) {
            $this->markTestSkipped('only for openApi 2.0');
        }

        $this->setPaths();

        $cfg = config('swagger-lume');
        $security = [
            'new_api_key_securitye' => [
                'type' => 'apiKey',
                'name' => 'api_key_name',
                'in' => 'query',
            ],
        ];
        $cfg['security'] = $security;
        config(['swagger-lume' => $cfg]);

        tap(new Generator)->generateDocs();

        $this->assertTrue(file_exists($this->jsonDocsFile()));

        $response = $this->get(config('swagger-lume.routes.docs'));

        $this->assertResponseOk();

        $this->assertStringContainsString('new_api_key_securitye', $response->response->getContent());
        $this->seeJson($security);
    }

    /** @test */
    public function canGenerateApiJsonFileWithSecurityDefinitionOpenApi3()
    {
        if (! $this->isOpenApi()) {
            $this->markTestSkipped('only for openApi 3.0');
        }

        $this->setPaths();

        $cfg = config('swagger-lume');
        $security = [
            'new_api_key_security' => [
                'type' => 'apiKey',
                'name' => 'api_key_name',
                'in' => 'query',
            ],
        ];
        $cfg['security'] = $security;
        $cfg['swagger_version'] = '3.0';
        config(['swagger-lume' => $cfg]);

        tap(new Generator)->generateDocs();

        $this->assertTrue(file_exists($this->jsonDocsFile()));

        $response = $this->get(config('swagger-lume.routes.docs'));

        $this->assertResponseOk();

        $content = $response->response->getContent();

        $this->assertStringContainsString('new_api_key_security', $content);
        $this->assertStringContainsString('oauth2', $content);
        $this->seeJson($security);
    }
}
