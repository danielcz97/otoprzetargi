<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionTypeResource\Pages;
use App\Models\TransactionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class TransactionTypeResource extends Resource
{
    protected static ?string $model = TransactionType::class;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('model_type')
                    ->default(fn() => request()->query('model_type')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionTypes::route('/'),
            'create' => Pages\CreateTransactionType::route('/create'),
            'edit' => Pages\EditTransactionType::route('/{record}/edit'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['model_type'] = request()->query('model_type');
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['model_type'] = request()->query('model_type');
        return $data;
    }
}
