<?php

namespace SwaggerLume;

use Illuminate\Support\Facades\File;
use OpenApi\Annotations\Server;
use OpenApi\Generator as OpenApiGenerator;
use OpenApi\Util;
use Symfony\Component\Yaml\Dumper as YamlDumper;
use Symfony\Component\Yaml\Yaml;

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
                $generator = new OpenApiGenerator();
                $finder = Util::finder($appDir, $excludeDirs);
                $swagger = $generator->generate($finder);
            } else {
                $swagger = \Swagger\scan($appDir, ['exclude' => $excludeDirs]);
            }

            if (config('swagger-lume.paths.base') !== null) {
                if (version_compare(config('swagger-lume.swagger_version'), '3.0', '>=')) {
                    $swagger->servers = [
                        new Server(['url' => config('swagger-lume.paths.base')]),
                    ];
                } else {
                    $swagger->basePath = config('swagger-lume.paths.base');
                }
            }

            $filename = sprintf('%s/%s', $docDir, config('swagger-lume.paths.docs_json'));
            $swagger->saveAs($filename);

            $security = new SecurityDefinitions();
            $security->generate($filename);

            self::makeYamlCopy($filename);
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

    protected static function makeYamlCopy($filename)
    {
        if (config('swagger-lume.generate_yaml_copy')) {
            $path = sprintf('%s/%s', config('swagger-lume.paths.docs'), config('swagger-lume.paths.docs_yaml'));
            $yamlContent = (new YamlDumper(2))->dump(
                json_decode(file_get_contents($filename), true),
                20,
                0,
                Yaml::DUMP_OBJECT_AS_MAP ^ Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE
            );

            file_put_contents($path, $yamlContent);
        }
    }
}
