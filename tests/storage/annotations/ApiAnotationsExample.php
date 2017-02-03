<?php

/**
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     schemes={"http"},
 *     host=SWAGGER_LUME_CONST_HOST,
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Swagger Lume API",
 *         @SWG\Contact(
 *             email="darius@matulionis.lt"
 *         ),
 *     )
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_auth",
 *   type="oauth2",
 *   authorizationUrl="/api/oauth",
 *   flow="implicit",
 *   scopes={
 *     "read:projects": "read your projects",
 *     "write:projects": "modify projects"
 *   }
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="query",
 *   name="api_key"
 * )
 */

/**
 * @SWG\SecurityScheme(
 *   securityDefinition="X-Api-Token",
 *   type="apiKey",
 *   in="header",
 *   name="X-Api-Token"
 * )
 */



/**
 * @SWG\Get(
 *      path="/projects",
 *      operationId="getProjectsList",
 *      tags={"Projects"},
 *      summary="Get list of projects",
 *      description="Returns list of projects",
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *       @SWG\Response(response=400, description="Bad request"),
 *     )
 *
 * Returns lis of projects
 */


/**
 * @SWG\Get(
 *      path="/projects/{id}",
 *      operationId="getProjectById",
 *      tags={"Projects"},
 *      summary="Get project information",
 *      description="Returns project data",
 *      @SWG\Parameter(
 *          name="id",
 *          description="Project id",
 *          required=true,
 *          type="integer",
 *          in="path"
 *      ),
 *      @SWG\Response(
 *          response=200,
 *          description="successful operation"
 *       ),
 *      @SWG\Response(response=400, description="Bad request"),
 *      @SWG\Response(response=404, description="Resource Not Found"),
 * )
 *
 */