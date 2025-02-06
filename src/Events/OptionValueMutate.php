<?php

namespace SmartCms\Contractors\Events;

use SmartCms\Contractors\Models\Contractor;
use SmartCms\Contractors\Service\ContractorCalculator;
use SmartCms\Store\Models\Product;

class OptionValueMutate
{
   public function __invoke(array &$data, Product $owner)
   {
      $contractor = Contractor::query()->where('id', $owner->contractor_id)->first() ?? Contractor::query()->first();
      if (!$contractor || !isset($data['origin_price'])) {
         return;
      }
      $data['price'] = ContractorCalculator::calculate($data['origin_price'], $contractor);
   }
}
