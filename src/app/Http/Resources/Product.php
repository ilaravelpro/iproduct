<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 7/21/20, 6:35 PM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iProduct\iApp\Http\Resources;

use iLaravel\Core\iApp\Http\Resources\File;
use iLaravel\Core\iApp\Http\Resources\Resource;

class  Product extends Resource
{
    public function toArray($request)
    {
        $data = parent::toArray($request);
        if ($this->tags && count($this->tags)) $data['tags'] = $this->tags->pluck('title')->toArray();
        if ($this->attachments->count()) {
            $fileModel = imodal('File');
            $data['attachments'] = [];
            foreach (['galleries'] as $item) {
                $galleries = $this->attachments()->wherePivot('type', $item)->get();
                if ($galleries->count()) {
                    foreach ($galleries as $index => $gallery) {
                        $data['attachments'][$item]['items'][$index] = File::collection($fileModel::where('post_id', $gallery->id)->get()->keyBy('mode'));
                    }
                }
            }
        }
        $alert_modal = imodal('ProductAlert');
        if (auth()->check() && ($alerts = $alert_modal::where('creator_id', auth()->id())->where('product_id', $this->id)->get()) && $alerts->count()) {
            $data['alerts'] = Resource::collection($alerts);
            $data['is_alert_discount'] = $alerts->where('type', 'discount')->first() instanceof $alert_modal;
            $data['is_alert_stock'] = $alerts->where('type', 'stock')->first() instanceof $alert_modal;
        }
        if (auth()->id()) $data['is_favorite'] = $this->favoritors->where('pivot.user_id', auth()->id())->first() instanceof (imodal('User'));
        unset($data['favoritors']);
        return $data;
    }
}
