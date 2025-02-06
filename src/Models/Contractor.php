<?php

namespace SmartCms\Contractors\Models;

use SmartCms\Contractors\Service\ContractorCalculator;
use SmartCms\Core\Models\BaseModel;
use SmartCms\Store\Models\Currency;
use SmartCms\Store\Models\Product;

class Contractor extends BaseModel
{
    protected $guarded = [];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    protected static function booted()
    {
        static::updating(function ($entity) {
            if ($entity->isDirty('rate')) {
                $products = $entity->products()->get();
                foreach ($products as $product) {
                    $product->price = ContractorCalculator::calculate($product->origin_price, $entity);
                    $product->saveQuietly();
                    Product::recalculatePrice($product);
                }
            }
        });
    }
}
