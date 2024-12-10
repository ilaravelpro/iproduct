<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/21/20, 6:35 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iProduct\iApp\Http\Resources;

use iLaravel\Core\iApp\Http\Resources\File;
use iLaravel\Core\iApp\Http\Resources\Resource;

class PriceOld extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        unset($data['product'], $data['warehouse']);
        return $data;
    }
}
