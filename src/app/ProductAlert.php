<?php
/*
 * Author: Amirhossein Jahani | iAmir.net
 * Email: me@iamir.net
 * Mobile No: +98-9146941147
 * Last modified: 2021/05/20 Thu 03:24 PM IRDT
 * Copyright (c) 2020-2022. Powered by iAmir.net
 */

namespace iLaravel\iProduct\iApp;

class ProductAlert extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'NMPTA';
    public static $s_start = 24300000;
    public static $s_end = 728999999;


    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }

    public function product()
    {
        return $this->belongsTo(imodal('Product'));
    }

    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $rules = [];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'product_id' => "required|exists:products,id",
                    'type' => "required|in:stock,discount",
                    /*'email' => "nullable|string",
                    'mobile' => "required|string",*/
                ]);
                break;
        }
        return $rules;
    }
}
