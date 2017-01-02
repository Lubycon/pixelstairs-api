<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => [
        "http://aws.lubycon.com",
        "http://localhost:3000",
        "http://localhost:3003",
        "chrome-extension://fhbjgbiflinjbdggehcddcbncdddomop"
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
