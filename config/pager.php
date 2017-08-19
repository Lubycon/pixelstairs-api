<?php

$tableName = (object)[
    "Content" => with(new App\Models\Content)->getTable(),
    "Comment" => with(new App\Models\Comment)->getTable(),
];

return [
    "comparision"           => [
        "multipleQueryDivider" => "||",
    ],
    "default"               => [
        "pageSize" => [
            "basic" => 20,
            "max"   => 100,
        ],
    ],
    "searchKeyConversion"   => [
        "contentId" => $tableName->Content . ".id",
        "featured"  => $tableName->Content . ".like_count",
        "latest"    => $tableName->Content . ".created_at",
        "RAW"       => "RAW", // Special key for raw query
    ],
    "searchValueConversion" => [
        "isNull" => null,
    ],
    "partsModel"            => [
        "Comment" => [
            [
                "join_table_name"       => $tableName->Content,
                "join_table_key_column" => "id",
                "base_table_key_column" => $tableName->Comment . ".content_id",
            ],
        ],
    ],
];
