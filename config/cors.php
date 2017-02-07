<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => json_decode(env("ALLOW_ORIGIN")),
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'requiredHeader' => ["x-mitty-language"],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
