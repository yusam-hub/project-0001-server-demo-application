<?php

namespace App\Http\Controllers\Api;

use App\ApiClients\ClientAuthAppSdk;
use App\Model\Authorize\AppAuthorizeModel;
use App\Model\Authorize\DemoAuthorizeModel;
use App\Model\Database\AppModel;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ControllerMiddlewareTrait;
use YusamHub\Project0001ClientAuthSdk\Servers\AppUserTokenServer;
use YusamHub\Project0001ClientAuthSdk\Servers\Models\AppUserTokenAuthorizeModel;
use YusamHub\Project0001ClientAuthSdk\Tokens\JwtAuthAppTokenHelper;
use YusamHub\Project0001ClientAuthSdk\Tokens\JwtAuthAppUserTokenHelper;

abstract class BaseTokenApiHttpController extends BaseApiHttpController implements ControllerMiddlewareInterface
{
    use ControllerMiddlewareTrait;
    const TOKEN_KEY_NAME = 'X-Token';
    const SIGN_KEY_NAME = 'X-Sign';
    const AUTH_ERROR_CODE_40101 = 40101;
    const AUTH_ERROR_CODE_40102 = 40102;
    const AUTH_ERROR_CODE_40103 = 40103;
    const AUTH_ERROR_CODE_40104 = 40104;
    const AUTH_ERROR_CODE_40105 = 40105;
    const AUTH_ERROR_CODE_40106 = 40106;
    const AUTH_ERROR_MESSAGES = [
        self::AUTH_ERROR_CODE_40101 => 'Invalid identifier in head',
        self::AUTH_ERROR_CODE_40102 => 'Fail load by identifier',
        self::AUTH_ERROR_CODE_40103 => 'Fail load payload data',
        self::AUTH_ERROR_CODE_40104 => 'Fail use payload data as identifier',
        self::AUTH_ERROR_CODE_40105 => 'Token expired',
        self::AUTH_ERROR_CODE_40106 => 'Invalid hash body',
    ];

    protected function getContent(Request $request): string
    {
        if (strtoupper($request->getMethod()) === 'GET') {
            $content = http_build_query($request->query->all());
        } else {
            $content = $request->getContent();
        }
        return $content;
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function apiAuthorizeHandle(Request $request): void
    {
        if (property_exists($this, 'apiAuthorizePathExcludes')) {
            if (in_array($request->getRequestUri(), $this->apiAuthorizePathExcludes)) {
                return;
            }
        }
        $appUserTokenServer = new AppUserTokenServer(
            new ClientAuthAppSdk(),
            $request->headers->get(self::TOKEN_KEY_NAME,''),
            $request->headers->get(self::SIGN_KEY_NAME,''),
            $this->getContent($request)
        );

        try {

            AppUserTokenAuthorizeModel::Instance()->assign($appUserTokenServer->getAuthorizeModelOrFail());

        } catch (\Throwable $e) {

            if ($e instanceof \RuntimeException) {
                throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException(json_decode($e->getMessage(), true));
            }

            throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                self::TOKEN_KEY_NAME => 'Invalid value',
                'detail' => $e->getMessage(),
                'code' => $e->getCode(),
                'class' => get_class($e)
            ]);

        }
    }
}