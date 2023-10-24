<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiSwaggerController;
use App\Http\Controllers\Api\BaseTokenApiHttpController;
use App\Model\Authorize\DemoAuthorizeModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class V1ControllerApi extends BaseTokenApiHttpController
{
    const MODULE_CURRENT = ApiSwaggerController::MODULE_V1;
    const TO_MANY_REQUESTS_CHECK_ENABLED = false;
    const DEFAULT_TOO_MANY_REQUESTS_TTL = 60;

    protected array $apiAuthorizePathExcludes = [
        '/api/' . self::MODULE_CURRENT
    ];

    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::controllerMiddlewareRegister(static::class, 'apiAuthorizeHandle');

        static::routesAdd($routes, ['OPTIONS', 'GET'],sprintf('/api/%s', self::MODULE_CURRENT), 'getApiHome');
        static::routesAdd($routes, ['OPTIONS', 'GET'],sprintf('/api/%s/test', self::MODULE_CURRENT), 'getApiTest');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getApiHome(Request $request): array
    {
        return [];
    }

    /**
     * @OA\Get(
     *   tags={"default"},
     *   path="/test",
     *   summary="Get test with authorize token",
     *   deprecated=false,
     *   security={{"XTokenScheme":{}},{"XSignScheme":{}}},
     *   @OA\Response(response=200, description="OK", @OA\MediaType(mediaType="application/json", @OA\Schema(
     *        @OA\Property(property="status", type="string", example="ok"),
     *        @OA\Property(property="data", type="array", example="array", @OA\Items(
     *        )),
     *        example={"status":"ok","data":{}},
     *   ))),
     *   @OA\Response(response=400, description="Bad Request", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     *   @OA\Response(response=429, description="Too Many Requests", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     *   @OA\Response(response=401, description="Unauthorized", @OA\MediaType(mediaType="application/json", @OA\Schema(ref="#/components/schemas/ResponseErrorDefault"))),
     * );
     */

    public function getApiTest(Request $request): array
    {
        return [
            'DemoAuthorizeModel' => (array) DemoAuthorizeModel::Instance()
        ];
    }
}