<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjectTypeResource\Pages;
use App\Models\ObjectType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ObjectTypeResource extends Resource
{
    protected static ?string $model = ObjectType::class;
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
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListObjectTypes::route('/'),
            'create' => Pages\CreateObjectType::route('/create'),
            'edit' => Pages\EditObjectType::route('/{record}/edit'),
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
