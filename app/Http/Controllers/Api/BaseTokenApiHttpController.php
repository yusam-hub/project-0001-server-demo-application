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
            $localCacheKey = md5($accessToken);

            $clientAuthAppSdk = new ClientAuthAppSdk();

            if ($this->getRedisKernel()->connection()->has($localCacheKey)) {

                DemoAuthorizeModel::Instance()->assign($this->getRedisKernel()->connection()->get($localCacheKey));

            } else {

                $accessTokenInfo = $clientAuthAppSdk->getApiAppAccessToken($accessToken);

                if (is_null($accessTokenInfo) || !isset($accessTokenInfo['data'])) {
                    throw new \Exception("Invalid access token", 40110);
                }

                DemoAuthorizeModel::Instance()->assign($accessTokenInfo['data']);

            }

            if (DemoAuthorizeModel::Instance()->appId !== $clientAuthAppSdk->getIdentifierId()) {
                throw new \Exception("Fail use payload data as identifier", 40111);
            }

            $server_time = curl_ext_time_utc();
            if (DemoAuthorizeModel::Instance()->expired >= $server_time) {
                throw new \Exception("Access token expired", 40112);
            }

        } catch (\Throwable $e) {

            throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                'token' => 'Invalid value',
                'detail' => $e->getMessage(),
                'code' => $e->getCode()
            ]);

        }
    }
}