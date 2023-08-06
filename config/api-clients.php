<?php

return [
    \App\ApiClients\ClientAuthAppSdk::class => [
        'isDebugging' => true,
        'baseUrl' => 'http://192.168.0.110:10001',
        'storageLogFile' => app_ext()->getStorageDir('/logs/') . 'curl-ext-debug-app-sdk.log',
        "identifierId" => 2,
        "privateKey" => "-----BEGIN PRIVATE KEY-----\nMIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBANT6kTU5XIb+dIbb\nCNztDc+Zrfat7pjzai/cQ+7XD+3DG2d/uZvYRVkQX2Z1O17Mfg+KDhjjlhWbwjxx\n3pCJ41RkkMnryYYUoP1J5CoRRrXjzH9oel954r+sMSCaPNOSMShMRCLZbTmuyC6f\nq6MIf1ozPufg6bjyRklzhhuXAr9vAgMBAAECgYEAgnbjkKkMxx0EoL94VG1acnt/\nl4bAjC1ANQVHD/3qIgnMtcAGITOAd6iR6B+UOOQnrLkzHYf8nkwF1iUi44O0HxVP\nfZL9auwBNJILg4cpcPjIgeZY8lebm21TGAuXFXUZaxh/iXr1/E3At8AMiW971Bbo\nHVqjvJuWAOT7EK8kUVECQQD95JuDFoFLxq6xt/3ytd7htHUF37cvBFNkiFyNiARQ\nZO8MTaHrsfnlaSpyiWP5uMJPJeKSjAaH9gJuEhTOI3KzAkEA1r8Juhxp7FG9SVFD\nGMhD+OOlIU0S4dt1IztotH8IzKbXjxgSIuDjqEg96AIcZMoqwUrSP86vPnacedQm\nwZiuVQJBAL2lWkgTsmagtPLI5aa7FQ3w1oyZq+Ixz/zDIqcRV2+ZxTmib3V3zpr+\n8Bb36zaoPHQUb2ZZs7MxHmWLmgDUV6MCQC4UmlK8bCJQu+xyqpIzzxomRzXpIwci\nH3Wq9uHcbJf1qUXEZYfkeBWRQu95HHyoQRpvIsScZlBiBVvDIXnjeG0CQQCrizV/\nKEMdZCmOaApihf8tykgJm1R27i9Gh0PEFuLyqlC3Bk0US4tc3O7rIABrv+FUvwQZ\nEgFpF6kaH3G1BVa+\n-----END PRIVATE KEY-----\n"
    ],
];