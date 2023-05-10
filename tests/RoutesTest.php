<?php

namespace Tests;

class RoutesTest extends LumenTestCase
{
    /** @test */
    public function cantAccessJsonFile()
    {
        $jsonUrl = config('swagger-lume.routes.docs');

        $this->get($jsonUrl);

        $this->assertResponseStatus(404);
    }

    /** @test */
    public function canAccessJsonFile()
    {
        $jsonUrl = config('swagger-lume.routes.docs');

        $this->setPaths()->createJsonDocumentationFile();

        $this->get($jsonUrl);

        $this->assertResponseOk();
    }

    /** @test */
    public function canAccessDocumentationInterface()
    {
        $this->setPaths();

        $this->app->prepareForConsoleCommand();

        $response = $this->get(config('swagger-lume.routes.api'));

        $this->assertResponseOk();

        $this->assertStringContainsString($this->docs_url, $response->response->getContent());
    }

    /** @test */
    public function itCanServeAssets()
    {
        $response = $this->get(swagger_lume_asset('swagger-ui.css'));

        $this->assertResponseOk();

        $this->assertStringContainsString('.swagger-ui', $response->response->getContent());
    }

    /** @test */
    public function userCanAccessOauth2Redirect()
    {
        $response = $this->get(config('swagger-lume.routes.oauth2_callback'));

        $this->assertResponseOk();

        $this->assertStringContainsString('swaggerUIRedirectOauth2', $response->response->getContent());
        $this->assertStringContainsString('oauth2.auth.code', $response->response->getContent());
    }
}
