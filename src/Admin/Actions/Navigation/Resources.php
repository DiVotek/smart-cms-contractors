<?php

namespace SmartCms\Contractors\Admin\Actions\Navigation;

use SmartCms\Contractors\Admin\Resources\ContractorResource;

class Resources
{
   public function __invoke(array &$items)
   {
      $items =  array_merge([
         ContractorResource::class,
      ], $items);
   }
}
