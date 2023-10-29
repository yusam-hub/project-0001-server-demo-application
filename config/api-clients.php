<?php

return [
    \App\ApiClients\ClientAuthAppSdk::class => [
        'isDebugging' => true,
        'baseUrl' => 'https://auth.mayapur.pp.ru.loc',
        'storageLogFile' => app_ext()->getStorageDir('/logs/') . 'curl-ext-debug-app-sdk.log',
        "identifierId" => app_ext_env("CLIENT_AUTH_APP_SDK_APP_ID", 0),
        "privateKey" =>  app_ext_env("CLIENT_AUTH_APP_SDK_APP_PRIVATE_KEY", '')
    ],
    \App\ApiClients\ClientS3Sdk::class => [
        'channel' => 'app',
        'isDebugging' => true,
        'bucketName' => app_ext_env("CLIENT_S3_SDK_BUCKET_NAME", ''),
        'args' => [
            'version' => 'latest',
            'region' => 'us-east-1',
            'use_path_style_endpoint' => true,
            'endpoint' => app_ext_env("CLIENT_S3_SDK_ENDPOINT", 'localhost'),
            'credentials' => [
                'key' => app_ext_env("CLIENT_S3_SDK_KEY", ''),
                'secret' => app_ext_env("CLIENT_S3_SDK_SECRET", ''),
            ],
        ]
    ],
];