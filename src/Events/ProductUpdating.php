<?php

namespace SmartCms\Contractors\Events;

use SmartCms\Contractors\Models\Contractor;
use SmartCms\Contractors\Service\ContractorCalculator;
use SmartCms\Store\Models\Product;

class ProductUpdating
{
   public function __invoke(Product &$product)
   {
      if (isset($product->origin_price)) {
         $contractor = Contractor::query()->find($product->contractor_id);
         if (!$contractor) {
            throw new \Exception('Contractor not found');
         }
         $product->price = ContractorCalculator::calculate($product->origin_price, $contractor);
      }
   }
}
