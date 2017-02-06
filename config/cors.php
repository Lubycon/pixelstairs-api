<?php

return [
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'requiredHeader' => ["x-mitty-language"],
    'exposedHeaders' => [],
    'maxAge' => 0,
    'hosts' => [],
];
