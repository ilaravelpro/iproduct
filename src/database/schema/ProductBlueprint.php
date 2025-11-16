<?php

namespace iLaravel\iProduct\Database\Schema;
class ProductBlueprint extends \iLaravel\Core\Database\Schema\PostBlueprint
{
    public $i_afters = [
        "image_id" => [
            "collection_id" => ["foreignTable", "%s_collections", "single"],
        ],
        "slug" => [
            "code",
            "quantity_default" => "bigInteger",
            "quantity_min" => "bigInteger",
            "quantity_max" => "bigInteger",
            "sales" => "bigInteger",
            "presales" => "bigInteger",
            "count" => "bigInteger",
            "avg_rates" => ["decimal", 2, 2],
            "year" => "integer",
            "type",
        ],
        "content" => [
            "is_bulk" => "boolean",
            "is_virtual" => "boolean",
            "is_stackable" => "boolean",
            "is_tax" => "boolean",
            "is_production" => "boolean",
            "is_produced" => "boolean",
            "is_buyout" => "boolean",
        ],
        "status" => [
            "first_produced_at" => "timestamp",
            "last_produced_at" => "timestamp",
            "produced_at" => "timestamp",
        ]
    ];
}
