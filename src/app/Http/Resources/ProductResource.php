<?php

namespace iLaravel\iProduct\iApp\Http\Resources;

use iLaravel\Core\iApp\Http\Resources\Resource;

class ProductResource extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $product_resource = iresource('Product');
        $product_data = $this->product ? (new $product_resource($this->product))->toArray($request) : [];
        unset($product_data['id']);
        $data = array_merge($data, $product_data);
        if ($this->prices && ($price = $this->prices->where('stock', '>', 0)->first()))
            $data['price_sale'] = $price->price_sale;
        unset($data['product']);
        return $data;
    }
}