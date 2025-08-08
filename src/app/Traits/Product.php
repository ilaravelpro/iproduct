<?php

namespace iLaravel\iProduct\iApp\Traits;

trait Product
{
    public function product()
    {
        return $this->belongsTo(imodal('Product'), 'product_id');
    }

    public function getTitleAttribute()
    {
        return $this->product->title;
    }

    public function getAttribute($key)
    {
        return !($value = parent::getAttribute($key)) && @$this->product ? @$this->product->getAttribute($key) : $value;
    }

    public function updateProduct($request)
    {
        $request->validationData();
        $requestArray = $request->toArray();
        $product = $this->product()->updateOrCreate(["model" => class_name(static::class), "model_id" => $this->id], []);
        $exceptAdditional = array_keys(method_exists($product, 'rules') ? $product->rules($request, 'additional', $product) : imodal("Product")::getRules($request, 'additional', $product));
        $exceptAdditional = array_map(function ($item) {
            return explode('.', $item)[0];
        }, $exceptAdditional);
        $rules = tap($this->rules($request, 'product', $this) ?: $this->getProductRules($request));
        $keys = array_keys($rules);
        $fields = handel_fields(array_values(array_unique($exceptAdditional)), $keys, $requestArray);
        $dataProduct = [];
        foreach ($fields as $value)
            if (_has_key($requestArray, $value))
                $dataProduct = _set_value($dataProduct, $value, _get_value($requestArray, $value));
        foreach ($dataProduct as $index => $item) {
            if (substr($index, 0, 3) === 'is_' || substr($index, 0, 4) === 'has_') {
                $product->$index = in_array($item, ['true', 'false', '0', '1']) ? intval($item == "true" || $item == "1") : $item;
            } else $product->$index = $item;
        }
        $product->save();
        $product->additionalUpdate($request);
        $this->product_id = $product->id;
        $this->save();
        return $product;
    }

    public function getProductRules($request, $context = null, $action = null)
    {
        $rules = imodal("Product")::getRules($request, $action ?: (@tap($context ?: $this)->product ? "update" : "store"), @(tap($context ?: $this))->product);
        $rules['status'] = 'nullable|in:' . join(',', $this->_statuses());
        return $rules;
    }


    public function alerts()
    {
        return $this->hasMany(imodal('ProductAlert'), 'product_id', 'product_id');
    }


    public function tags()
    {
        return $this->belongsToMany(imodal('Tag'), 'products_tags', 'product_id');
    }

    public function terms()
    {
        return $this->belongsToMany(imodal('Term'), 'products_terms', 'product_id');
    }


    public function favoritors()
    {
        return $this->belongsToMany(imodal('User'), 'products_favorites', 'product_id', 'user_id');
    }

    public function attachments()
    {
        return $this->belongsToMany(imodal('Attachment'), 'products_attachments', 'product_id');
    }

    public function articles()
    {
        return $this->belongsToMany(imodal('Article'), 'products_articles', 'product_id');
    }

    public function accessories()
    {
        return $this->belongsToMany(imodal('Product'), 'products_accessories', 'product_id', 'accessory_id');
    }

    public function awards()
    {
        return $this->hasMany(imodal('ProductAward'), 'product_id', 'product_id');
    }

    public function editions()
    {
        return $this->hasMany(imodal('ProductEdition'), 'product_id', 'product_id');
    }

    public function prices()
    {
        return $this->hasMany(imodal('Price'), 'product_id', 'product_id');
    }

    public function price_olds()
    {
        return $this->hasMany(imodal('PriceOld'), 'product_id', 'product_id');
    }

    public function additionalUpdate($request = null, $additional = null, $parent = null)
    {
        $this->updateProduct($request);
        parent::additionalUpdate($request, $additional, $parent);
    }

    public static function findByAny($value)
    {
        if (!count(static::$find_names)) return false;
        return static::where('id', static::id($value))->orWhereHas('product', function ($q) use ($value) {
            foreach (array_values(static::$find_names) as $index => $name) {
                $q->{$index > 0 ? "orWhere" : "where"}($name, $value);
            }
        })->first();
    }
}