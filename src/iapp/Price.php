<?php
/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 12/20/20, 8:27 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iProduct\iApp;

class Price extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = "IPPP";
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }
}
