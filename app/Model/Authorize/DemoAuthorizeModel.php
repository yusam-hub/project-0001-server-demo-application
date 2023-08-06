<?php

namespace App\Model\Authorize;

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
    public ?int $userId = null;
}