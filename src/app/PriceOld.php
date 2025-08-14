<?php
/*
 * Author: Amirhossein Jahani | iAmir.net
 * Email: me@iamir.net
 * Mobile No: +98-9146941147
 * Last modified: 2021/05/20 Thu 03:24 PM IRDT
 * Copyright (c) 2020-2022. Powered by iAmir.net
 */

namespace iLaravel\iProduct\iApp;

class PriceOld extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'NMPEO';
    public static $s_start = 24300000;
    public static $s_end = 728999999;
    protected $table = "price_olds";

    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }

    public function product()
    {
        return $this->belongsTo(imodal('Product'));
    }

    public function parent()
    {
        return $this->belongsTo(imodal('Price'));
    }

    public function warehouse()
    {
        return ($model = imodal('Warehouse')) ? $this->belongsTo($model) : null;
    }
    public function firends()
    {
        return $this->hasMany(imodal('PriceOld'), 'price_id', 'price_id');
    }

    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $rules = [];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'price_id' => "required|exists:prices,id",
                    'product_id' => "required|exists:products,id",
                    'price_first' => "required|numeric",
                    'unit' => "nullable|string",
                    'quantity' => "nullable|double",
                    'stock' => "nullable|numeric",
                    'price_sale' => "required|numeric",
                    'discount_type' => "nullable|in:percent,value",
                    'currency' => "nullable|in:IRT",
                    'discount_start_at' => "nullable|date_format:Y-m-d H:i:s",
                    'discount_end_at' => "nullable|date_format:Y-m-d H:i:s",
                ]);
                if (imodal('Warehouse'))
                    $rules['warehouse_id'] = "required|exists:warehouses,id";
                break;
        }
        return $rules;
    }
}
