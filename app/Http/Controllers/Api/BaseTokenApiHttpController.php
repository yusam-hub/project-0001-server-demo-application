<?php

namespace App\Http\Controllers\Api;

use App\ApiClients\ClientAuthAppSdk;
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

            $clientAuthAppSdk = new ClientAuthAppSdk();
            $accessTokenInfo = $clientAuthAppSdk->getApiAppAccessToken($accessToken);

            if (is_null($accessTokenInfo) || !isset($accessTokenInfo['data'])) {
                throw new \Exception("Invalid access token", 40101);
            }

            /**
             * todo: $accessTokenInfo - добавить в кеш на срок жизни
             */

            DemoAuthorizeModel::Instance()->payload = new AccessTokenPayload($accessTokenInfo['data']);

        } catch (\Throwable $e) {

            throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                'token' => 'Invalid value',
                'detail' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

        }
    }
}