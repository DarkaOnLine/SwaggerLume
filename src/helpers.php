<?php

use SwaggerLume\Exceptions\SwaggerLumeException;

if (! function_exists('swagger_ui_dist_path')) {
    /**
     * Returns swagger-ui composer dist path.
     *
     * @param  null  $asset
     * @return string
     *
     * @throws \SwaggerLume\Exceptions\SwaggerLumeException
     */
    function swagger_ui_dist_path($asset = null)
    {
        $allowed_files = [
            'favicon-16x16.png',
            'favicon-32x32.png',
            'oauth2-redirect.html',
            'swagger-ui-bundle.js',
            'swagger-ui-bundle.js.map',
            'swagger-ui-standalone-preset.js',
            'swagger-ui-standalone-preset.js.map',
            'swagger-ui.css',
            'swagger-ui.css.map',
            'swagger-ui.js',
            'swagger-ui.js.map',
        ];

        $path = base_path('vendor/swagger-api/swagger-ui/dist/');
        if (! $asset) {
            return realpath($path);
        }

        if (! in_array($asset, $allowed_files)) {
            throw new SwaggerLumeException(sprintf('(%s) - this L5 Swagger asset is not allowed', $asset));
        }

        return realpath($path.$asset);
    }
}

if (! function_exists('swagger_lume_asset')) {
    /**
     * Returns asset from swagger-ui composer package.
     *
     * @param $asset string
     * @return string
     *
     * @throws \SwaggerLume\Exceptions\SwaggerLumeException
     */
    function swagger_lume_asset($asset)
    {
        $file = swagger_ui_dist_path($asset);

        if (! file_exists($file)) {
            throw new SwaggerLumeException(sprintf('Requested L5 Swagger asset file (%s) does not exists', $asset));
        }

        return route('swagger-lume.asset', ['asset' => $asset, 'v' => md5($file)], config('swagger-lume.force_https')) ?? app('request')->secure();
    }
}
