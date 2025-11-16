<?php

namespace iLaravel\iProduct\iApp\Extends;

use iLaravel\Core\iApp\Model;

class Product extends Model
{
    use \iLaravel\iProduct\iApp\Traits\Product;

    public $set_slug = true;
    public static $find_names = ['title', 'slug'];

    public $with_resource = ['prices' => 'Price'];
    public $with_resource_single = ['terms', 'articles', 'accessories', 'awards', 'editions', 'price_olds'];
    public $with_resource_data = ["collection"];
}
