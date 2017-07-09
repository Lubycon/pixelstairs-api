<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => json_decode(env("ALLOW_ORIGIN")),
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'requiredHeader' => [
        "x-pixel-version",
        "x-pixel-country",
        "x-pixel-language",
        "x-pixel-device"
    ],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
