<?php

namespace SwaggerLume;

use Illuminate\Support\Facades\File;
use OpenApi\Annotations\Server;

class Generator
{
    public static function generateDocs()
    {
        $appDir = config('swagger-lume.paths.annotations');
        $docDir = config('swagger-lume.paths.docs');
        if (! File::exists($docDir) || is_writable($docDir)) {
            // delete all existing documentation
            if (File::exists($docDir)) {
                File::deleteDirectory($docDir);
            }

            self::defineConstants(config('swagger-lume.constants') ?: []);

            File::makeDirectory($docDir);
            $excludeDirs = config('swagger-lume.paths.excludes');

            if (version_compare(config('swagger-lume.swagger_version'), '3.0', '>=')) {
                $swagger = \OpenApi\scan($appDir, ['exclude' => $excludeDirs]);
            } else {
                $swagger = \Swagger\scan($appDir, ['exclude' => $excludeDirs]);
            }

            if(config('swagger-lume.servers'))
                $swagger->servers = self::getServers();            

            if (config('swagger-lume.paths.base') !== null) {
                if (version_compare(config('swagger-lume.swagger_version'), '3.0', '>=')) {
                    $swagger->servers = [
                        new Server(['url' => config('swagger-lume.paths.base')]),
                    ];
                } else {
                    $swagger->basePath = config('swagger-lume.paths.base');
                }
            }

            $filename = $docDir.'/'.config('swagger-lume.paths.docs_json');
            $swagger->saveAs($filename);

            $security = new SecurityDefinitions();
            $security->generate($filename);
        }
    }

    protected static function defineConstants(array $constants)
    {
        if (! empty($constants)) {
            foreach ($constants as $key => $value) {
                defined($key) || define($key, $value);
            }
        }
    }

    protected static function getServers() {

        // Split comas
        $servers = preg_split ("/\,/", config('swagger-lume.servers'));

        // Create array with servers (versions)
        $temp_servers = [];

        foreach ($servers as $server)
            array_push($temp_servers, ["url"=> $server]);

        return $temp_servers;

    }

}
