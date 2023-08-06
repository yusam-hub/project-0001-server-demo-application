<?php

namespace App\Model\Authorize;

use YusamHub\Project0001ClientAuthSdk\Payloads\AccessTokenPayload;

class DemoAuthorizeModel
{
    protected static ?DemoAuthorizeModel $instance = null;
    public static function Instance(): DemoAuthorizeModel
    {
        if (is_null(static::$instance)) {
            static::$instance = new DemoAuthorizeModel();
        }
        return static::$instance;
    }

    public ?AccessTokenPayload $payload = null;
}