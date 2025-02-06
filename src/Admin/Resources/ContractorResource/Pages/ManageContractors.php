<?php

namespace SmartCms\Contractors\Admin\Resources\ContractorResource\Pages;

use Filament\Resources\Pages\ManageRecords;
use SmartCms\Contractors\Admin\Resources\ContractorResource;
use Filament\Actions;
use Filament\Forms\Components\Toggle;

class ManageContractors extends ManageRecords
{
   protected static string $resource = ContractorResource::class;

   protected function getHeaderActions(): array
   {
      return [
         Actions\Action::make(_hints('help'))
            ->iconButton()
            ->icon('heroicon-o-question-mark-circle')
            ->modalDescription(__('Contractors are used to calculate the price of the product'))
            ->modalFooterActions([]),
         Actions\Action::make('settings')->icon('heroicon-o-cog-6-tooth')->form([
            Toggle::make('enabled')->default(setting('contractor.enabled', true)),
         ])
            ->fillForm(function () {
               return [
                  'enabled' => setting('contractor.enabled', true),
               ];
            })->action(function ($data) {
               setting([
                  'contractor.enabled' => $data['enabled'],
               ]);
            }),
         Actions\CreateAction::make(),
      ];
   }
}
