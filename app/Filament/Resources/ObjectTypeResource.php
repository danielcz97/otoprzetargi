<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjectTypeResource\Pages;
use App\Filament\Resources\ObjectTypeResource\RelationManagers;
use App\Models\ObjectType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObjectTypeResource extends Resource
{
    protected static ?string $model = ObjectType::class;
    protected static ?string $navigationLabel = 'Typy obiektów';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
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
}
