<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Film API Documentation",
 *     description="API documentation for Film management",
 *     @OA\Contact(
 *         email="your-email@example.com"
 *     )
 * )
 * @OA\Server(
 *     url="/",
 *     description="Main API Server"
 * )
 */
abstract class Controller
{
    //
}
