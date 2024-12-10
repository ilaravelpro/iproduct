<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 9/1/20, 7:44 AM
 * Copyright (c) 2020. Powered by iamir.net
 */

namespace iLaravel\iProduct\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Exceptions\iException;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

class ProductController extends \iLaravel\Core\iApp\Http\Controllers\API\ApiController
{
    public $order_list = ['id', 'title', 'slug', 'summary', 'content', 'order', 'status', 'approved_at'];

    public function filters($request, $model, $parent = null, $operators = [])
    {
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
            [
                'name' => 'title',
                'title' => _t('title'),
                'type' => 'text'
            ],
            [
                'name' => 'terms',
                'title' => _t('terms'),
                'rule' => 'required|exists_serial:Term',
                'type' => 'text'
            ],
            [
                'name' => 'slug',
                'title' => _t('slug'),
                'type' => 'text'
            ],
            [
                'name' => 'summary',
                'title' => _t('summary'),
                'type' => 'text'
            ],
            [
                'name' => 'content',
                'title' => _t('content'),
                'type' => 'text'
            ],
            [
                'name' => 'order',
                'title' => _t('order'),
                'type' => 'number'
            ],
        ];
        return [$filters, [], $operators];
    }

    public function query_filter_type($model, $filter, $params, $current)
    {
        switch ($params->type){
            case 'terms':
                $termModel = imodal('Term');
                $model->whereHas('terms', function ($query) use ($params, $termModel) {
                    $query->whereIn('terms.id', array_map(function ($serial) use($termModel) {return $termModel::id($serial); }, $params->value));
                    return $query;
                });
                $current['terms'] = $filter->value;
                break;
        }
        return $current;
    }

    public function favorite(Request $request, $record)
    {
        if (($record = $this->model::findBySerial($record))) {
            if ($favoritor = $record->favoritors()->where('pivot.user_id', auth()->id())->first()) {
                $this->statusMessage = 'به لیست علاقه‌مندی های شما افزوده شد.';
                $record->favoritors()->attach(auth()->id());
            }else $this->statusMessage = 'از لیست علاقه‌مندی های شما حذف شد.';
                $record->favoritors()->detach(auth()->id());
            return ['data' => []];
        }else
            throw new iException('اطلاعاتی یافت نشد.');
    }

    public function send_alert(Request $request, $record)
    {
        if (($record = $this->model::findBySerial($record))) {
            if (@$record->alerts->count())
                isms_send("modals.products.alerts.stock", array_filter($record->alerts->map(function ($item) {
                    return @$item->creator->mobile->text;
                })->toArray(), 'strlen'), [
                    'product' => $record->title,
                    'url' => asset($record->type . '/' . ($record->slug?:$record->serial))
                ]);
            $this->statusMessage = 'باموفقیت به لیست اعلان موجودی پیامک ارسال گردید.';
            return ['data' => []];
        }else
            throw new iException('اطلاعاتی یافت نشد.');
    }
}
