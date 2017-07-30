<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => json_decode(env("SERVICE_ALLOW_ORIGIN")),
    'adminAllowedOrigins' => json_decode(env("ADMIN_ALLOW_ORIGIN")),
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'requiredHeader' => [
        "x-pixel-version",
    ],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
