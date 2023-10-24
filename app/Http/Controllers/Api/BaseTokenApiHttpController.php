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

        $token = $request->headers->get(self::TOKEN_KEY_NAME,'');
        $sign = $request->headers->get(self::SIGN_KEY_NAME,'');

        try {

            if (!empty($sign)) {
                $appUserKeyId = intval($token);
                $serviceKey = $sign;

                //todo: need cache $appUserKeyId + $serviceKey на 600 секунд (что бы каждый раз не дергать апи авторизации)
                $clientAuthAppSdk = new ClientAuthAppSdk();
                $appUserKeyService = $clientAuthAppSdk->getApiAppUserKeyService($appUserKeyId, $serviceKey);

                if (is_null($appUserKeyService)) {
                    throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                        self::TOKEN_KEY_NAME => 'Invalid value',
                        self::SIGN_KEY_NAME => 'Invalid value',
                    ]);
                }

                if (!isset($appUserKeyService['data']['appId'],$appUserKeyService['data']['userId'],$appUserKeyService['data']['deviceUuid'])) {
                    throw new \YusamHub\AppExt\Exceptions\HttpUnauthorizedAppExtRuntimeException([
                        self::TOKEN_KEY_NAME => 'Invalid value',
                        self::SIGN_KEY_NAME => 'Invalid value',
                    ]);
                }

                DemoAuthorizeModel::Instance()->appId = intval($appUserKeyService['data']['appId']);
                DemoAuthorizeModel::Instance()->userId = intval($appUserKeyService['data']['userId']);
                DemoAuthorizeModel::Instance()->deviceUuid = strval($appUserKeyService['data']['deviceUuid']);
                return;
            }

            $serverTime = curl_ext_time_utc();
            JWT::$timestamp = $serverTime;

            $appUserHeads = JwtAuthAppUserTokenHelper::fromJwtAsHeads($token);

            if (is_null($appUserHeads->aid) || is_null($appUserHeads->uid) || is_null($appUserHeads->did)) {
                throw new \Exception(self::AUTH_ERROR_MESSAGES[self::AUTH_ERROR_CODE_40101], self::AUTH_ERROR_CODE_40101);
            }

            //todo: need cache $appUserKey['publicKey'], при это hashKey должен быть в токене передан

            $clientAuthAppSdk = new ClientAuthAppSdk();
            $appUserKey = $clientAuthAppSdk->getApiAppUserKey($appUserHeads->uid, $appUserHeads->did);

            if (!isset($appUserKey['publicKey'])) {
                throw new \Exception(self::AUTH_ERROR_MESSAGES[self::AUTH_ERROR_CODE_40102], self::AUTH_ERROR_CODE_40102);
            }

            $appUserTokenPayload = JwtAuthAppUserTokenHelper::fromJwtAsPayload($token, $appUserKey['publicKey']);

            if (is_null($appUserTokenPayload->aid) || is_null($appUserTokenPayload->uid) || is_null($appUserTokenPayload->did) || is_null($appUserTokenPayload->iat) || is_null($appUserTokenPayload->exp) || is_null($appUserTokenPayload->hb)) {
                throw new \Exception(self::AUTH_ERROR_MESSAGES[self::AUTH_ERROR_CODE_40103], self::AUTH_ERROR_CODE_40103);
            }

            if ($appUserTokenPayload->aid != $appUserHeads->aid || $appUserTokenPayload->uid != $appUserHeads->uid || $appUserTokenPayload->did != $appUserHeads->did) {
                throw new \Exception(self::AUTH_ERROR_MESSAGES[self::AUTH_ERROR_CODE_40104], self::AUTH_ERROR_CODE_40104);
            }

            if ($serverTime < $appUserTokenPayload->iat and $serverTime > $appUserTokenPayload->exp) {
                throw new \Exception(self::AUTH_ERROR_MESSAGES[self::AUTH_ERROR_CODE_40105], self::AUTH_ERROR_CODE_40105);
            }

            if (strtoupper($request->getMethod()) === 'GET') {
                $content = http_build_query($request->query->all());
            } else {
                $content = $request->getContent();
            }
            if (md5($content) !== $appUserTokenPayload->hb) {
                throw new \Exception(self::AUTH_ERROR_MESSAGES[self::AUTH_ERROR_CODE_40106], self::AUTH_ERROR_CODE_40106);
            }

            DemoAuthorizeModel::Instance()->appId = $appUserTokenPayload->aid;
            DemoAuthorizeModel::Instance()->userId = $appUserTokenPayload->uid;
            DemoAuthorizeModel::Instance()->deviceUuid = $appUserTokenPayload->did;

        } catch (\Throwable $e) {

            if ($e instanceof HttpUnauthorizedAppExtRuntimeException) {
                throw $e;
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