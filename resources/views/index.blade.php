
<!DOCTYPE html>
<html>
<head>
    <title>{{config('swagger-lume.api.title')}}</title>
    <link href={{config('swagger-lume.paths.assets_public').'/css/typography.css'}} media='screen' rel='stylesheet'
          type='text/css'/>
    <link href={{config('swagger-lume.paths.assets_public').'/css/reset.css'}} media='screen' rel='stylesheet' type='text/css'/>
    <link href={{config('swagger-lume.paths.assets_public').'/css/screen.css'}} media='screen' rel='stylesheet' type='text/css'/>
    <link href={{config('swagger-lume.paths.assets_public').'/css/reset.css'}} media='print' rel='stylesheet' type='text/css'/>
    <link href={{config('swagger-lume.paths.assets_public').'/css/screen.css'}} media='print' rel='stylesheet' type='text/css'/>
    <script type="text/javascript" src={{config('swagger-lume.paths.assets_public').'/lib/shred.bundle.js'}}></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/jquery-1.8.0.min.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/jquery.slideto.min.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/jquery.wiggle.min.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/jquery.ba-bbq.min.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/handlebars-2.0.0.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/underscore-min.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/backbone-min.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/swagger-client.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/swagger-ui.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/highlight.7.3.pack.js'}} type='text/javascript'></script>
    <script src={{config('swagger-lume.paths.assets_public').'/lib/marked.js'}} type='text/javascript'></script>

    <!-- enabling this will enable oauth2 implicit scope support -->
    <script src={{config('swagger-lume.paths.assets_public').'/lib/swagger-oauth.js'}} type='text/javascript'></script>
    <script type="text/javascript">
        $(function () {
            var url = window.location.search.match(/url=([^&]+)/);
            if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
            } else {
                url = "{!! $urlToDocs !!}";
            }
            window.swaggerUi = new SwaggerUi({
                url: url,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function(swaggerApi, swaggerUi){
                    @if(isset($requestHeaders))
                    @foreach($requestHeaders as $requestKey => $requestValue)
                    window.authorizations.add("{{$requestKey}}", new ApiKeyAuthorization("{{$requestKey}}", "{{$requestValue}}", "header"));
                    @endforeach
                    @endif
                    if(typeof initOAuth == "function") {
                        /*
                         initOAuth({
                         clientId: "your-client-id",
                         realm: "your-realms",
                         appName: "your-app-name"
                         });
                         */
                    }
                    $('pre code').each(function(i, e) {
                        hljs.highlightBlock(e)
                    });
                },
                onFailure: function(data) {
                    log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                sorter : "alpha"
            });

            function addApiKeyAuthorization() {
                var key = $('#input_apiKey')[0].value;
                if(key && key.trim() != "") {
                    window.authorizations.add('{{$apiKeyVar}}', new ApiKeyAuthorization('{{$apiKeyVar}}', key, "{{$apiKeyInject}}"));
                }
            }

            $('#input_apiKey').change(function() {
                addApiKeyAuthorization();
            });

            // if you have an apiKey you would like to pre-populate on the page for demonstration purposes
            // just put it in the .env file, API_AUTH_TOKEN variable
            @if($apiKey)
                $('#input_apiKey').val("{{$apiKey}}");
                addApiKeyAuthorization();
            @endif

            window.swaggerUi.load();
        });
    </script>
</head>

<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <a id="logo" href="http://swagger.io">swagger</a>
        <form id='api_selector'>
            <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text"/></div>
            <div class='input'><input placeholder="api_key" id="input_apiKey" name="apiKey" type="text"/></div>
            <div class='input'><a id="explore" href="#">Explore</a></div>
        </form>
    </div>
</div>

<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
