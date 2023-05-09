<?php

namespace Tests;

class ForceHttpsTest extends LumenTestCase
{
    /** @test */
    public function defaultFromEnv()
    {
        $this->assertNull(env('SWAGGER_LUME_FORCE_HTTPS'));
        $this->assertStringContainsString('http://', swagger_lume_asset('swagger-ui.css'));
    }

    /** @test */
    public function forcesHttpsFromConfig()
    {
        config(['swagger-lume.force_https' => true]);

        $this->assertStringContainsString('https://', swagger_lume_asset('swagger-ui.css'));

        config(['swagger-lume.force_https' => false]);
    }

    /** @test */
    public function doesNotForceHttpsFromConfig()
    {
        config(['swagger-lume.force_https' => false]);

        $this->assertStringContainsString('http://', swagger_lume_asset('swagger-ui.css'));
    }
}
