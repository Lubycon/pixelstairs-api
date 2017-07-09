<?php

$tableName = (object)[
    "Content" => with(new App\Models\Content)->getTable(),
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
        "featured" => $tableName->Content . ".like_count",
        "latest"   => $tableName->Content . ".created_at",
        "RAW"      => "RAW", // Special key for raw query
    ],
    "searchValueConversion" => [
        "isNull" => null,
    ],
    "partsModel"            => [
//        "HouseSale" => [
//            [
//                "join_table_name"       => $tableName->Agency,
//                "join_table_key_column" => "uidx",
//                "base_table_key_column" => $tableName->HouseSale . ".uidx",
//            ],
//        ],
    ],
];
