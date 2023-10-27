<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Request;

class ApiSwaggerController extends \YusamHub\AppExt\SymfonyExt\Http\Controllers\ApiSwaggerController
{
    const MODULE_ADMIN = 'admin';
    const MODULE_V1 = 'v1';

    const MODULES = [
        self::MODULE_ADMIN,
        self::MODULE_V1,
    ];

    protected static function getSwaggerModules(): array
    {
        return self::MODULES;
    }

    protected function getOpenApiScanPaths(Request $request, string $module): array
    {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . ucfirst($module)
        ];
    }
}