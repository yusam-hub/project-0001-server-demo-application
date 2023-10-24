<?php

return [
    \App\ApiClients\ClientAuthAppSdk::class => [
        'isDebugging' => true,
        'baseUrl' => 'https://esa.mayapur.pp.ru.loc',
        'storageLogFile' => app_ext()->getStorageDir('/logs/') . 'curl-ext-debug-app-sdk.log',
        "identifierId" => app_ext_env("CLIENT_AUTH_APP_SDK_APP_ID", 0),
        "privateKey" =>  app_ext_env("CLIENT_AUTH_APP_SDK_APP_PRIVATE_KEY", '')
    ],
];