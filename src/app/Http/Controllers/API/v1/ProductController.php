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
            [
                'name' => 'collection_id',
                'title' => _t('collection'),
                'type' => 'text'
            ],
        ];
        $model->with(['product', 'prices'/*, 'creators', 'sounds', 'electronics'*/]);
        if ($request->related_id && ($product = imodal('Product')::findByAny($request->related_id))) {
            $model->where('id', '!=', $product->id);
            switch ($request->type_data) {
                case 'collection_products':
                    $model->whereHas('product', function ($q) use ($product) {
                        $q->where('collection_id', $product->product->collection_id);
                    });
                    break;
                case 'products_terms':
                    $model->whereExists(function ($query) use ($product) {
                        $query->select(\DB::raw(1))
                            ->from('products_terms')
                            ->whereColumn('products_terms.product_id', 'products.product_id');
                        $query->whereIn('products_terms.term_id', $product->terms->pluck('id')->toArray());
                    });
                    break;
                default:
                    $model
                        ->where(function ($query) use ($product) {
                            $query->whereExists(function ($query) use ($product) {
                                $query->select(\DB::raw(1))
                                    ->from('products_terms')
                                    ->whereColumn('products_terms.product_id', 'products.product_id');
                                $query->whereIn('products_terms.term_id', $product->terms->pluck('id')->toArray());
                            });
                            $query->orWhereExists(function ($query) use ($product) {
                                $query->select(\DB::raw(1))
                                    ->from('products_creators')
                                    ->whereColumn('products_creators.product_id', 'products.id');
                                $query->whereIn('products_creators.creator_id', $product->creators->pluck('id')->toArray());
                            });
                        });
                    break;
            }
        }
        if (is_array($request->years) && count($request->years)) {
            $start = (new \Morilog\Jalali\Jalalian($request->years[0], 01, 01))->toCarbon()->format('Y-m-d H:i:s');
            $end = (new \Morilog\Jalali\Jalalian($request->years[1], 01, 01))->toCarbon()->format('Y-m-d H:i:s');
            $model->whereBetween('first_published_at', [$start, $end]);
        }
        if (auth()->check()) {
            $model->with(['favoritors']);
            if ($request->is_my_alerts && $request->is_my_alerts == 1) {
                $model->whereHas('alerts', function ($q) use ($request) {
                    $q->where('creator_id', auth()->id());
                });
            }
            if ($request->is_my_favorites && $request->is_my_favorites == 1) {
                $model->whereHas('favoritors', function ($q) use ($request) {
                    $q->where('users.id', auth()->id());
                });
            }
        }
        return [$filters, [], $operators];
    }

    public function query_filter_type($model, $filter, $params, $current)
    {
        switch ($params->type){
            case 'terms':
                $termModel = imodal('Term');
                if ($params->value)
                    $model->whereHas('terms', function ($query) use ($params, $termModel) {
                        $query->whereIn('terms.id', array_map(function ($serial) use ($termModel) {
                            return $termModel::id($serial) ? : (@$termModel::findByAny($serial)->id);
                        }, $params->value));
                        return $query;
                    });
                $current['terms'] = $filter->value;
                break;
            case 'collection_id':
                $termModel = imodal('ProductCollection');
                if ($params->value)
                    $model->whereHas('product', function ($query) use ($params, $termModel) {
                        $query->whereIn('products.collection_id', array_map(function ($serial) use ($termModel) {
                            return $termModel::id($serial) ? : (@$termModel::findByAny($serial)->id);
                        }, $params->value));
                        return $query;
                    });
                $current['collection_id'] = $filter->value;
                break;
        }
        return $current;
    }

    public function favorite(Request $request, $record)
    {
        if ($record = $this->model::findByAny($record)) {
            if ($record->favoritors()->where('users.id', auth()->id())->first()) {
                $this->statusMessage = _t("Removed from your wishlist.");
                $record->favoritors()->detach(auth()->id());
            }else {
                $this->statusMessage = _t("Added to your wishlist.");
                $record->favoritors()->attach(auth()->id());
            }
            return ['data' => $this->_show($request, $record)];
        }else
            throw new iException("No information found.");
    }

    public function send_alert(Request $request, $record)
    {
        if ($record = $this->model::findByAny($record)) {
            if (@$record->alerts->count())
                isms_send("modals.products.alerts.stock", array_filter($record->alerts->map(function ($item) {
                    return @$item->creator->mobile->text;
                })->toArray(), 'strlen'), [
                    'product' => $record->title,
                    'url' => asset($record->type . '/' . ($record->slug?:$record->serial))
                ]);
            $this->statusMessage = _t("SMS was successfully sent to the stock notification list.");
            return ['data' => []];
        }else
            throw new iException("No information found.");
    }
}
