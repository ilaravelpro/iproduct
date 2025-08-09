<?php
/*
 * Author: Amirhossein Jahani | iAmir.net
 * Email: me@iamir.net
 * Mobile No: +98-9146941147
 * Last modified: 2021/05/20 Thu 03:24 PM IRDT
 * Copyright (c) 2020-2022. Powered by iAmir.net
 */

namespace iLaravel\iProduct\iApp;

class Product extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'NMPT';
    public static $s_start = 24300000;
    public static $s_end = 728999999;
    public static $find_names = ['slug', 'title'];

    public $files = ['image'];
    public $with_resource_data = ['collection', 'terms'];
    public function creator()
    {
        return $this->belongsTo(imodal('User'));
    }

    public function model_item()
    {
        return $this->hasOne(imodal($this->model), 'product_id');
    }

    public function tags()
    {
        return $this->belongsToMany(imodal('Tag'), 'products_tags');
    }

    public function terms()
    {
        return $this->belongsToMany(imodal('Term'), 'products_terms');
    }

    public function attachments()
    {
        return $this->belongsToMany(imodal('Attachment'), 'products_attachments');
    }

    public function articles()
    {
        return $this->belongsToMany(imodal('Article'), 'products_articles');
    }

    public function accessories()
    {
        return $this->belongsToMany(imodal('Product'), 'products_accessories', 'product_id', 'accessory_id');
    }

    public function favoritors()
    {
        return $this->belongsToMany(imodal('User'), 'products_favorites', 'product_id', 'user_id');
    }

    public function collection()
    {
        return $this->belongsTo(imodal('ProductCollection'), 'collection_id');
    }

    public function awards()
    {
        return $this->hasMany(imodal('ProductAward'));
    }

    public function editions()
    {
        return $this->hasMany(imodal('ProductEdition'));
    }

    public function prices()
    {
        return $this->hasMany(imodal('Price'));
    }

    public function price_olds()
    {
        return $this->hasMany(imodal('PriceOld'));
    }

    public function getItemModelAttribute() {
        return (imodal($this->model))::find($this->model_id);
    }


    public function additionalUpdate($request = null, $additional = null, $parent = null)
    {
        _save_child($this->prices(), $request->prices?:[], Price::class, ['product_id' => $this->id]);
        _save_child($this->awards(), $request->awards?:[], ProductAward::class, ['product_id' => $this->id]);
        _save_child($this->editions(), $request->editions?:[], ProductEdition::class, ['product_id' => $this->id]);
        parent::additionalUpdate($request, $additional, $parent);
    }

    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $arg1 = $arg1 instanceof static ? $arg1 : (is_integer($arg1) ? static::find($arg1) : (is_string($arg1) ? static::findBySerial($arg1) : $arg1));
        $rules = [];
        $additionalRules = [
            'image_id' => 'nullable|exists:posts,id',
            'image_file' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
            'terms.*.value' => "required|exists:terms,id",
            'tags.*' => "nullable",
            'attachments.galleries.uploads.*' => "required|mimes:jpeg,jpg,png,gif|max:5120",
            'attachments.galleries.deletes.*' => "required|exists_serial:Attachment",

            'prices.*.id' => "nullable|exists:prices,id",
            'prices.*.warehouse_id' => "required|exists:warehouses,id",
            'prices.*.unit' => "nullable|string",
            'prices.*.quantity' => "nullable|double",
            'prices.*.price_first' => "required|numeric",
            'prices.*.price_sale' => "required|numeric",
            'prices.*.discount_type' => "nullable|in:percent,value",
            'prices.*.discount_value' => "nullable|numeric|min:1",
            'prices.*.currency' => "nullable|in:IRT",
            'prices.*.discount_start_at' => "nullable|date_format:Y-m-d H:i:s",
            'prices.*.discount_end_at' => "nullable|date_format:Y-m-d H:i:s",

            'awards.*.article_id' => "nullable|exists:posts,id",
            'awards.*.title' => "required|string",
            'awards.*.description' => "nullable|string",
            'awards.*.awarded_at' => "nullable|date_format:Y-m-d H:i:s",
            'awards.*.status' => 'nullable|in:' . implode( ',', iconfig('status.awards', iconfig('status.global'))),

            'editions.*.article_id' => "nullable|exists:posts,id",
            'editions.*.title' => "required|string",
            'editions.*.count' => 'nullable|numeric|min:0',
            'editions.*.price_first' => "nullable|numeric|min:0",
            'editions.*.price_sale' => "nullable|numeric|min:0",
            'editions.*.edition_at' => "nullable|date_format:Y-m-d H:i:s",
            'editions.*.status' => 'nullable|in:' . implode( ',', iconfig('status.editions', iconfig('status.global'))),
        ];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'collection_id' => "nullable|exists:products_accessories,id",
                    'title' => "required|string",
                    'slug' => ['nullable','string'],
                    'weight' => "nullable|string",
                    'size_x' => "nullable|string",
                    'size_y' => "nullable|string",
                    'size_z' => "nullable|string",
                    'quantity_default' => "nullable|string",
                    'quantity_min' => "nullable|string",
                    'quantity_max' => "nullable|string",
                    'sales' => "nullable|string",
                    'presales' => "nullable|string",
                    'avg_rates' => "nullable|string",
                    'type' => "nullable|string",
                    'template' => "nullable|string",
                    'summary' => "nullable|string",
                    'content' => "nullable|string",
                    'is_virtual' => "nullable|boolean",
                    'is_stackable' => "nullable|boolean",
                    'is_shippable' => "nullable|boolean",
                    'is_tax' => "nullable|boolean",
                    'is_published' => "nullable|boolean",
                    'is_buyout' => "nullable|boolean",
                    'order' => "nullable|numeric",
                    'count_published' => "nullable|numeric",
                    'year_published' => "nullable|date_format:Y",
                    'first_published_at' => "nullable|date_format:Y-m-d H:i:s",
                    'last_published_at' => "nullable|date_format:Y-m-d H:i:s",
                    'published_at' => "nullable|date_format:Y-m-d H:i:s",
                    'status' => 'nullable|in:' . join( ',', iconfig('status.book_creators', iconfig('status.global'))),
                ], $additionalRules);
                break;
            case 'additional':
                $rules = $additionalRules;
                break;
        }
        return $rules;
    }
}
