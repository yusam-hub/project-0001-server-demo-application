<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Admin\AdminControllerApi;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use YusamHub\AppExt\SymfonyExt\Http\Controllers\BaseHttpController;

class ApiAdminRoutes extends BaseHttpController
{
    public static function routesRegister(RoutingConfigurator $routes): void
    {
        AdminControllerApi::routesRegister($routes);
    }

}