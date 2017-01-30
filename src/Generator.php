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
            $swagger = \Swagger\scan($appDir, $excludeDirs);

            $filename = $docDir.'/api-docs.json';
            $swagger->saveAs($filename);
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
}
