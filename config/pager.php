<?php

return [
    "searchKeyConversion" => [
        "id" => "id",
        "userId" => "users.id",
        "contentId" => "contents.id"
    ],
    "searchValueConversion" => [
        "isNull" => null,
    ],
    "partsModel" => [
        "content" => [
            [
                "join_table_name" => 'users',
                "join_table_key_column" => "id",
                "base_table_key_column" => "contents.user_id",
            ],
        ],
        "comment" => [
            [
                "join_table_name" => 'contents',
                "join_table_key_column" => "id",
                "base_table_key_column" => "comments.content_id",
            ],
        ],
    ],
];