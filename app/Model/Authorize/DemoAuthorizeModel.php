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

    public ?string $type = null;
    public ?int $expired = null;
    public ?int $userId = null;
    public ?int $appId = null;
    public ?string $deviceUuid = null;

    public function assign(array $properties): void
    {
        foreach($properties as $k => $v){
            if (property_exists($this, $k)) {
                $this->{$k} = $v;
            }
        }
    }
}