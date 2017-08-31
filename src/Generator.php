<?php

namespace SwaggerLume;

use Illuminate\Support\Facades\File;

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
            $swagger = \Swagger\scan($appDir, ['exclude' => $excludeDirs]);

            if (config('swagger-lume.paths.base') !== null) {
                $swagger->basePath = config('swagger-lume.paths.base');
            }

            $filename = $docDir.'/'.config('swagger-lume.paths.docs_json');
            $swagger->saveAs($filename);

            self::appendSecurityDefinitions($filename);
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

    protected static function appendSecurityDefinitions($filename)
    {
        $securityConfig = config('swagger-lume.security', []);

        if (is_array($securityConfig) && ! empty($securityConfig)) {
            $documentation = collect(
                json_decode(file_get_contents($filename))
            );

            $securityDefinitions = $documentation->has('securityDefinitions') ?
                collect($documentation->get('securityDefinitions')) :
                collect();

            foreach ($securityConfig as $key => $cfg) {
                $securityDefinitions->offsetSet($key, self::arrayToObject($cfg));
            }

            $documentation->offsetSet('securityDefinitions', $securityDefinitions);

            file_put_contents($filename, $documentation->toJson());
        }
    }

    public static function arrayToObject($array)
    {
        return json_decode(json_encode($array));
    }
}
