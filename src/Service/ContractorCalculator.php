<?php

namespace SmartCms\Contractors\Service;

use SmartCms\Contractors\Models\Contractor;

class ContractorCalculator
{
   public static function calculate(float $price, Contractor $contractor): float
   {
      $price = $price ?? 0;
      if (! is_numeric($price)) {
         $price = 0;
      }
      if ($price < 0) {
         $price = 0;
      }
      $currency = $contractor->currency;
      $decimals = $currency->decimal_place;
      $multiplier = pow(10, $decimals);
      $price = ceil($price * $contractor->rate * $multiplier) / $multiplier;
      return $price;
   }
}
