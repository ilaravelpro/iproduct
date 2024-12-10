<?php
/*
 * Author: Amirhossein Jahani | iAmir.net
 * Email: me@iamir.net
 * Mobile No: +98-9146941147
 * Last modified: 2021/05/20 Thu 03:24 PM IRDT
 * Copyright (c) 2020-2022. Powered by iAmir.net
 */

namespace iLaravel\iProduct\iApp;

class ProductEdition extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'NMED';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }

    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $arg1 = $arg1 instanceof static ? $arg1 : (is_integer($arg1) ? static::find($arg1) : (is_string($arg1) ? static::findBySerial($arg1) : $arg1));
        $rules = [];
        $additionalRules = [];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'title' => "required|string",
                    'count' => 'nullable|numeric|min:0',
                    'price_first' => "nullable|numeric|min:0",
                    'price_sale' => "nullable|numeric|min:0",
                    'edition_at' => "nullable|date_format:Y-m-d H:i:s",
                    'status' => 'nullable|in:' . join( ',', iconfig('status.editions', iconfig('status.products'))),
                ]);
                break;
            case 'additional':
                $rules = $additionalRules;
                break;
        }
        return $rules;
    }
}
