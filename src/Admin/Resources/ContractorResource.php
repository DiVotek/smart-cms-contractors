<?php

namespace SmartCms\Contractors\Admin\Resources;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use SmartCms\Core\Models\Translate;
use SmartCms\Core\Services\Schema;
use SmartCms\Core\Services\TableSchema;
use SmartCms\Contractors\Admin\Resources\ContractorResource\Pages as Pages;
use SmartCms\Contractors\Models\Contractor;
use SmartCms\Store\Models\Currency;

class ContractorResource extends Resource
{
    protected static ?string $model = Contractor::class;

    public static function getNavigationGroup(): ?string
    {
        return _nav('system');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getModelLabel(): string
    {
        return "Contractor";
    }

    public static function canDelete(Model $record): bool
    {
        return Contractor::query()->count() > 1;
    }

    public static function getPluralModelLabel(): string
    {
        return "Contractors";
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('currency_id')->options(Currency::query()->pluck('name', 'id')->toArray())->required(),
                TextInput::make('rate')->numeric()->helperText('Rate is the price of the contractor to the main currency')->required(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TableSchema::getName(),
                TextColumn::make('currency.name'),
                TextColumn::make('rate')->numeric(),
                TableSchema::getUpdatedAt(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContractors::route('/'),
        ];
    }
}
