<?php

namespace SmartCms\Contractors\Events;

use SmartCms\Contractors\Models\Contractor;
use SmartCms\Contractors\Service\ContractorCalculator;
use SmartCms\Store\Models\Product;

class ProductCreating
{
   public function __invoke(Product &$product)
   {
      if (isset($product->origin_price)) {
         return;
      }
      $contractor = Contractor::query()->where('id', $product->contractor_id)->first();
      if (!$contractor) {
         throw new \Exception('Contractor not found');
      }
      $product->price = ContractorCalculator::calculate($product->origin_price, $contractor);
   }
}
