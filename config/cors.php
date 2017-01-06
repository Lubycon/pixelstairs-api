<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => [
        "http://www.mittycompany.com",
        "http://admin.mittycompany.com",
        "http://localhost:3000",
        "http://localhost:3003",
    ],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'requiredHeader' => [
        "x-lubycon-device",
        "x-lubycon-country",
        "x-lubycon-language",
        "x-lubycon-version",
    ],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
