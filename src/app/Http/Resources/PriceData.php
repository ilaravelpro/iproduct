<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/21/20, 6:35 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iProduct\iApp\Http\Resources;

use iLaravel\Core\iApp\Http\Resources\ResourceData;

class PriceData extends ResourceData
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['price_sale'] = $this->price_sale;
        $data['price_sale_text'] = number_format($this->price_sale) . ' تومان';
        $data['text'] = number_format($this->price_sale) . ' تومان';
        return $data;
    }
}
