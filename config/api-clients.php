<?php

return [
    \App\ApiClients\ClientAuthAppSdk::class => [
        'isDebugging' => true,
        'baseUrl' => 'http://192.168.0.110:10001',
        'storageLogFile' => app_ext()->getStorageDir('/logs/') . 'curl-ext-debug-app-sdk.log',
        "identifierId" => app_ext_env("CLIENT_AUTH_APP_SDK_APP_ID", 0),
        "privateKey" =>  app_ext_env("CLIENT_AUTH_APP_SDK_APP_PRIVATE_KEY", '')
    ],
];