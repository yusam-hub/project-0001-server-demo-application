<?php

namespace App\ApiClients;

class ClientAuthApiAppSdk extends \YusamHub\Project0001ClientAuthSdk\ClientAuthApiAppSdk
{
    public function __construct()
    {
        parent::__construct(app_ext_config('api-clients.' . get_class($this)));
    }
}