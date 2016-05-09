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

        $this->app->prepareForConsoleCommand();

        $response = $this->get(config('swagger-lume.routes.api'));

        $this->assertResponseOk();

        $this->assertContains($this->auth_token_prefix, $response->response->getContent());

        $this->assertContains($this->auth_token, $response->response->getContent());

        $this->assertContains($this->key_var, $response->response->getContent());

        $this->assertContains($this->validator_url, $response->response->getContent());
    }
}
