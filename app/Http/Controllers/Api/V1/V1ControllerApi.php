<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiSwaggerController;
use App\Http\Controllers\Api\BaseApiHttpController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class V1ControllerApi extends BaseApiHttpController
{
    const MODULE_CURRENT = ApiSwaggerController::MODULE_V1;
    public static function routesRegister(RoutingConfigurator $routes): void
    {
        static::routesAdd($routes, ['OPTIONS', 'GET'],sprintf('/api/%s', self::MODULE_CURRENT), 'getApiHome');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getApiHome(Request $request): array
    {
        return [];
    }
}