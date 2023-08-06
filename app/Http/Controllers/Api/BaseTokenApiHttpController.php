<?php

namespace App\Http\Controllers\Api;

use App\ApiClients\ClientAuthApiAppSdk;
use App\Model\Authorize\DemoAuthorizeModel;
use Symfony\Component\HttpFoundation\Request;
use YusamHub\AppExt\SymfonyExt\Http\Interfaces\ControllerMiddlewareInterface;
use YusamHub\AppExt\SymfonyExt\Http\Traits\ControllerMiddlewareTrait;
use YusamHub\Project0001ClientAuthSdk\Payloads\AccessTokenPayload;

abstract class BaseTokenApiHttpController extends BaseApiHttpController implements ControllerMiddlewareInterface
{
    use ControllerMiddlewareTrait;
    const TOKEN_KEY_NAME = 'X-Token';

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

        $accessToken = $request->headers->get(self::TOKEN_KEY_NAME,'');

        try {

            $clientAuthApiAppSdk = new ClientAuthApiAppSdk();
            $accessTokenInfo = $clientAuthApiAppSdk->getAccessToken($accessToken);
            if (is_null($accessTokenInfo)) {
                throw new \Exception("Invalid access token", 40101);
            }

            DemoAuthorizeModel::Instance()->payload = new AccessTokenPayload($accessTokenInfo);

        } catch (\Throwable $e) {

            throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                'token' => 'Invalid value',
                'detail' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

        }
    }
}