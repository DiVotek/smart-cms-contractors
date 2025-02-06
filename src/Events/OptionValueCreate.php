<?php

namespace SmartCms\Contractors\Events;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use SmartCms\Contractors\Models\Contractor;
use SmartCms\Contractors\Service\ContractorCalculator;
use SmartCms\Store\Models\Product;

class OptionValueCreate
{
   public function __invoke(array &$form, Product $owner)
   {
      $contractor = Contractor::query()->where('id', $owner->contractor_id)->first() ?? Contractor::query()->first();
      if (!$contractor) {
         return;
      }
      $keyToReplace = null;
      foreach ($form as $key => $el) {
         if ($el->getName() == 'price') {
            $keyToReplace = $key;
            break;
         }
      }
      array_splice($form, $keyToReplace, 1, [
         TextInput::make('origin_price')->label('Contractor price')->numeric()->helperText('Price in the contractor currency')->suffix(function () use ($contractor) {
            return $contractor->currency->code . ' (' . $contractor->rate . ')';
         })->live(debounce: 1000)->afterStateUpdated(function ($get, $set) use ($contractor) {
            $set('price', ContractorCalculator::calculate($get('origin_price'), $contractor));
         }),
         TextInput::make('price')->label('Price')->disabled()->numeric()->helperText('Price in the main currency')->suffix(app('currency')->code),
      ]);
   }
}
