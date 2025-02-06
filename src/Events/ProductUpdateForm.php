<?php

namespace SmartCms\Contractors\Events;

use SmartCms\Contractors\Models\Contractor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use SmartCms\Contractors\Service\ContractorCalculator;

class ProductUpdateForm
{
   public function __invoke(array &$form)
   {
      $keyToReplace = null;
      foreach ($form as $key => $el) {
         if ($el->getName() == 'price') {
            $keyToReplace = $key;
            break;
         }
      }
      array_splice($form, $keyToReplace, 1, [
         Select::make('contractor_id')->options(Contractor::query()->pluck('name', 'id')->toArray())->required()->default(Contractor::query()->first()->id)->live()->afterStateUpdated(function ($get, $set) {
            $contractor = Contractor::query()->find($get('contractor_id'));
            if (!$contractor) {
               $set('price', 0);
               $set('origin_price', 0);
               return;
            }
            $set('price', ContractorCalculator::calculate($get('origin_price'), $contractor));
         }),
         TextInput::make('origin_price')->label('Contractor price')->numeric()->helperText('Price in the contractor currency')->suffix(function ($get) {
            $contractor = Contractor::query()->find($get('contractor_id'));
            if (!$contractor) {
               return '';
            }
            return $contractor->currency->code . ' (' . $contractor->rate . ')';
         })->live(debounce: 1000)->afterStateUpdated(function ($get, $set) {
            $contractor = Contractor::query()->find($get('contractor_id'));
            if (!$contractor) {
               return;
            }
            $set('price', ContractorCalculator::calculate($get('origin_price'), $contractor));
         }),
         TextInput::make('price')->label('Price')->disabled()->numeric()->helperText('Price in the main currency')->suffix(app('currency')->code),
      ]);
   }
}
