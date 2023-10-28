<?php

namespace App\AuthorizeServers;

use YusamHub\AppExt\Redis\RedisCacheUseFresh;

class AppUserTokenServer extends \YusamHub\Project0001ClientAuthSdk\Servers\AppUserTokenServer
{
    protected function getApiAppUserKeyService(int $appUserKeyId, string $serviceKey): ?array
    {
        return RedisCacheUseFresh::rememberExt(
            app_ext_redis_global()->connection(),
            app_ext_logger(),
            md5(__METHOD__ . $appUserKeyId . $serviceKey),
            true,
            false,
            RedisCacheUseFresh::CACHE_TTL_5_MINUTES,
            function () use($appUserKeyId, $serviceKey) {
                return $this->clientAuthAppSdk->getApiAppUserKeyService($appUserKeyId, $serviceKey);
            }
        );
    }

    protected function getApiAppUserKey(int $uid, string $did): ?array
    {
        return RedisCacheUseFresh::rememberExt(
            app_ext_redis_global()->connection(),
            app_ext_logger(),
            md5(__METHOD__ . $uid . $did),
            true,
            false,
            RedisCacheUseFresh::CACHE_TTL_5_MINUTES,
            function () use($uid, $did) {
                return $this->clientAuthAppSdk->getApiAppUserKey($uid, $did);
            }
        );
    }
}