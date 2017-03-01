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

        $this->setPaths()->crateJsonDocumentationFile();

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

        $this->assertContains($this->validator_url, $response->response->getContent());
    }
}
