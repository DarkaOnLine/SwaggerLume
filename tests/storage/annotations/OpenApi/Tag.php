<?php

namespace Tests\storage\annotations\OpenApi;

/**
 * @OA\Tag(
 *     name="project",
 *     description="Everything about your Projects",
 *     @OA\ExternalDocumentation(
 *         description="Find out more",
 *         url="http://swagger.io"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="user",
 *     description="Operations about user",
 *     @OA\ExternalDocumentation(
 *         description="Find out more about",
 *         url="http://swagger.io"
 *     )
 * )
 * @OA\ExternalDocumentation(
 *     description="Find out more about Swagger",
 *     url="http://swagger.io"
 * )
 */
class Tag
{
}
