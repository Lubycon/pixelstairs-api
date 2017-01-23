<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => [
        "http://mittycompany.com",
        "http://www.mittycompany.com",
        "http://admin.mittycompany.com",
        "http://localhost:3000",
        "http://localhost:3003",
        "http://mitty.api",
        "52.78.202.148",
        "chrome-extension://fhbjgbiflinjbdggehcddcbncdddomop"
    ],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'requiredHeader' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
