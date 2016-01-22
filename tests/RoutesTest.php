<?php

class RoutesTest extends LumenTestCase
{
    /** @test */
    public function cant_access_json_file()
    {
        $jsonUrl = config('swagger-lume.routes.docs');

        $this->get($jsonUrl);

        $this->assertResponseStatus(404);
    }

    /** @test */
    public function can_access_json_file()
    {
        $jsonUrl = config('swagger-lume.routes.docs');

        $this->setPaths()->crateJsonDocumentationFile();

        $this->get($jsonUrl);

        $this->assertResponseOk();
    }

    /** @test */
    public function can_access_documentation_interface()
    {
        $this->setPaths();

        $this->get(config('swagger-lume.routes.api'));

        $this->assertResponseOk();

        $this->app->prepareForConsoleCommand();
    }
}
