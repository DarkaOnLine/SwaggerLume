<?php

namespace Tests;

use SwaggerLume\Generator;

class ForceHttpsTest extends LumenTestCase
{   
    /** @test */
    public function defaultFromEnv()
    {   
        $force_https = getenv('SWAGGER_LUME_FORCE_HTTPS');
        $asset = swagger_lume_asset('swagger-ui.css');

        $this->assertStringContainsString('http://', $asset);
    }

    /** @test */
    public function forcesHttpsFromConfig()
    {
        config(['swagger-lume.force_https' => true]);

        $force_https = getenv('SWAGGER_LUME_FORCE_HTTPS');
        $asset = swagger_lume_asset('swagger-ui.css');

        $this->assertStringContainsString('https://', $asset);

        config(['swagger-lume.force_https' => false]);
    }

    /** @test */
    public function doesNotForceHttpsFromConfig()
    {
        config(['swagger-lume.force_https' => false]);

        $force_https = getenv('SWAGGER_LUME_FORCE_HTTPS');
        $asset = swagger_lume_asset('swagger-ui.css');

        $this->assertStringContainsString('http://', $asset);
    }
}
