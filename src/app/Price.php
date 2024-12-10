<?php
/*
 * Author: Amirhossein Jahani | iAmir.net
 * Email: me@iamir.net
 * Mobile No: +98-9146941147
 * Last modified: 2021/05/20 Thu 03:24 PM IRDT
 * Copyright (c) 2020-2022. Powered by iAmir.net
 */

namespace iLaravel\iProduct\iApp;

class Price extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'NMPE';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    public $with_resource_data = ['warehouse', 'product'];
    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }

    public function product()
    {
        return $this->belongsTo(imodal('Product'));
    }

    public function warehouse()
    {
        return $this->belongsTo(imodal('Warehouse'));
    }
    public function kids()
    {
        return $this->hasMany(imodal('PriceOld'), 'price_id');
    }

    public function getDiscountAmountAttribute()
    {
        $value = $this->discount_type == 'percent' ? (iproduct_round_currency(($this->price_sale * $this->disocunt_value) / 100)) : $this->disocunt_value;
        return $value >= $this->price_sale ? iproduct_round_currency($value * 0.8) : $value;
    }

    public function getBenefitAttribute()
    {
        return iproduct_round_currency($this->cost - $this->price_first);
    }

    public function getTaxAttribute()
    {
        return iproduct_round_currency($this->cost * 0.09, 100, 'ceil');
    }

    public function getAmountAttribute()
    {
        return iproduct_round_currency($this->cost + $this->tax);
    }

    public function getCostAttribute()
    {
        return iproduct_round_currency($this->price_sale - $this->discount_amount);
    }

    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $rules = [];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    //'product_id' => "required|exists:products,id",
                    'warehouse_id' => "required|exists:warehouses,id",
                    'price_first' => "required|numeric",
                    'price_sale' => "required|numeric",
                    'stock' => "nullable|numeric",
                    'discount_type' => "nullable|in:percent,value",
                    'currency' => "nullable|in:IRT",
                    'discount_start_at' => "nullable|date_format:Y-m-d H:i:s",
                    'discount_end_at' => "nullable|date_format:Y-m-d H:i:s",
                ]);
                break;
        }
        return $rules;
    }
}
