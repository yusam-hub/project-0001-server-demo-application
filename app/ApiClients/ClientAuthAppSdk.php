<?php

namespace App\ApiClients;

class ClientAuthAppSdk extends \YusamHub\Project0001ClientAuthSdk\ClientAuthAppSdk
{
    public function __construct()
    {
        parent::__construct(app_ext_config('api-clients.' . get_class($this)));
    }
}