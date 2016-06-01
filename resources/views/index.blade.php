<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{config('swagger-lume.api.title')}}</title>
    <link rel="icon" type="image/png" href="{{config('swagger-lume.paths.assets_public')}}/images/favicon-32x32.png"
          sizes="32x32"/>
    <link rel="icon" type="image/png" href="{{config('swagger-lume.paths.assets_public')}}/images/favicon-16x16.png"
          sizes="16x16"/>
    <link href='{{config('swagger-lume.paths.assets_public')}}/css/typography.css' media='screen' rel='stylesheet'
          type='text/css'/>
    <link href='{{config('swagger-lume.paths.assets_public')}}/css/reset.css' media='screen' rel='stylesheet'
          type='text/css'/>
    <link href='{{config('swagger-lume.paths.assets_public')}}/css/screen.css' media='screen' rel='stylesheet'
          type='text/css'/>
    <link href='{{config('swagger-lume.paths.assets_public')}}/css/reset.css' media='print' rel='stylesheet'
          type='text/css'/>
    <link href='{{config('swagger-lume.paths.assets_public')}}/css/print.css' media='print' rel='stylesheet'
          type='text/css'/>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/jquery-1.8.0.min.js'
            type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/jquery.slideto.min.js'
            type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/jquery.wiggle.min.js'
            type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/jquery.ba-bbq.min.js'
            type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/handlebars-2.0.0.js'
            type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/underscore-min.js' type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/backbone-min.js' type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/swagger-ui.js' type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/highlight.7.3.pack.js'
            type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/jsoneditor.min.js' type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/marked.js' type='text/javascript'></script>
    <script src='{{config('swagger-lume.paths.assets_public')}}/lib/swagger-oauth.js' type='text/javascript'></script>

    <!-- Some basic translations -->
    <!-- <script src='lang/translator.js' type='text/javascript'></script> -->
    <!-- <script src='lang/ru.js' type='text/javascript'></script> -->
    <!-- <script src='lang/en.js' type='text/javascript'></script> -->

    <script type="text/javascript">
        $(function () {
            var url = window.location.search.match(/url=([^&]+)/);
            if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
            } else {
                url = "{!! $urlToDocs !!}";
            }

            // Pre load translate...
            if (window.SwaggerTranslator) {
                window.SwaggerTranslator.translate();
            }
            window.swaggerUi = new SwaggerUi({
                url: url,
                dom_id: "swagger-ui-container",
                @if(array_key_exists('validatorUrl', get_defined_vars()))
                // This differentiates between a null value and an undefined variable
                validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
                @endif
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function (swaggerApi, swaggerUi) {
                    @if(isset($requestHeaders))
                    @foreach($requestHeaders as $requestKey => $requestValue)
                    window.swaggerUi.api.clientAuthorizations.add(
                        "{{$requestKey}}",
                        new SwaggerClient.ApiKeyAuthorization("{{$requestKey}}", "{{$requestValue}}", "header")
                    );
                    @endforeach
                            @endif

                    if (typeof initOAuth == "function") {
                        initOAuth({
                            clientId: "your-client-id",
                            clientSecret: "your-client-secret-if-required",
                            realm: "your-realms",
                            appName: "your-app-name",
                            scopeSeparator: ",",
                            additionalQueryStringParams: {}
                        });
                    }

                    if (window.SwaggerTranslator) {
                        window.SwaggerTranslator.translate();
                    }

                    $('pre code').each(function (i, e) {
                        hljs.highlightBlock(e)
                    });
                },

                onFailure: function (data) {
                    console.log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                jsonEditor: false,
                apisSorter: "alpha",
                defaultModelRendering: 'schema',
                showRequestHeaders: false
            });

            window.swaggerUi.load();

            $('#input_AppUrl').change(function(){
                var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization(
                        "AppUrl",
                        $(this).val(),
                        "header"
                    );
                window.swaggerUi.api.clientAuthorizations.add("AppUrl", apiKeyAuth);
            });
            $('#input_JwtToken').change(function(){
                 var apiKeyAuth = new SwaggerClient.ApiKeyAuthorization(
                        "WJWT-Authorization",
                        $(this).val(),
                        "header"
                    );
                window.swaggerUi.api.clientAuthorizations.add("WJWT-Authorization", apiKeyAuth);
            });

        });
    </script>
</head>

<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <a id="logo" href="http://swagger.io">swagger</a>
        <form id='api_selector'>
            <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl"
                                      type="text"/></div>
            <div class='input'><a id="explore" href="#" data-sw-translate>Explore</a></div>
        </form>    </div>

</div>

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div class="swagger-ui-wrap">
            <div class='input'><input placeholder="AppUrl" id="input_AppUrl" name="AppUrl" type="text"/></div>
            <div class='input'><input placeholder="JwtToken" id="input_JwtToken" name="JwtToken" type="text"/></div>
</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
