<?php

namespace App\ApiClients;

class ClientS3Sdk extends \YusamHub\S3Sdk\ClientS3Sdk
{
    protected ?string $channel = null;
    public function __construct()
    {
        parent::__construct(app_ext_config('api-clients.' . get_class($this)));
    }

    public function logDebug(string $message, array $context = []): void
    {
        if (!$this->isDebugging) return;

        app_ext_logger($this->channel)->debug($message, $context);
    }
}