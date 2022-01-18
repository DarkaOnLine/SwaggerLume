<?php

namespace SwaggerLume;

use Illuminate\Support\Facades\File;

class Generator
{
    public static function generateDocs()
    {
        $appDirs = config('swagger-lume.paths.annotations');
        $docDir = config('swagger-lume.paths.docs');
        if (!File::exists($docDir) || is_writable($docDir)) {
            // delete all existing documentation
            if (File::exists($docDir)) {
                File::deleteDirectory($docDir);
            }

            self::defineConstants(config('swagger-lume.constants') ?: []);

            File::makeDirectory($docDir);
            $excludeDirs = config('swagger-lume.paths.excludes');

            if (version_compare(config('swagger-lume.swagger_version'), '3.0', '>=')) {
                $appDirsWithExclude = array_map(function ($path) use ($excludeDirs) {
                    return \OpenApi\Util::finder($path, $excludeDirs);
                }, $appDirs);

                $swagger = \OpenApi\Generator::scan($appDirsWithExclude);
            } else {
                $swagger = \Swagger\scan($appDirs[0], ['exclude' => $excludeDirs]);
            }

            if (config('swagger-lume.paths.base') !== null) {
                $swagger->basePath = config('swagger-lume.paths.base');
            }

            $filename = $docDir.'/'.config('swagger-lume.paths.docs_json');
            $swagger->saveAs($filename);

            $security = new SecurityDefinitions();
            $security->generate($filename);
        }
    }

    protected static function defineConstants(array $constants)
    {
        if (!empty($constants)) {
            foreach ($constants as $key => $value) {
                defined($key) || define($key, $value);
            }
        }
    }
}
