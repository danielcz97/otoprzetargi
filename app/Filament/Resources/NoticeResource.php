<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NoticeResource\Pages;
use App\Models\Notice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;


class NoticeResource extends Resource
{
    protected static ?string $model = Notice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('User ID')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                Forms\Components\Textarea::make('body')
                    ->label('Body')
                    ->required(),
                Forms\Components\Textarea::make('excerpt')
                    ->label('Excerpt'),
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->required(),
                Forms\Components\TextInput::make('mime_type')
                    ->label('MIME Type'),
                Forms\Components\TextInput::make('comment_status')
                    ->label('Comment Status'),
                Forms\Components\TextInput::make('comment_count')
                    ->label('Comment Count')
                    ->numeric(),
                Forms\Components\TextInput::make('promote')
                    ->label('Promote')
                    ->numeric(),
                Forms\Components\TextInput::make('path')
                    ->label('Path'),
                Forms\Components\TextInput::make('terms')
                    ->label('Terms'),
                Forms\Components\TextInput::make('sticky')
                    ->label('Sticky')
                    ->numeric(),
                Forms\Components\TextInput::make('lft')
                    ->label('Left')
                    ->numeric(),
                Forms\Components\TextInput::make('rght')
                    ->label('Right')
                    ->numeric(),
                Forms\Components\TextInput::make('visibility_roles')
                    ->label('Visibility Roles'),
                Forms\Components\TextInput::make('type')
                    ->label('Type')
                    ->required(),
                Forms\Components\DateTimePicker::make('updated')
                    ->label('Updated')
                    ->required(),
                Forms\Components\DateTimePicker::make('created')
                    ->label('Created')
                    ->required(),
                Forms\Components\TextInput::make('cena')
                    ->label('Cena')
                    ->numeric(),
                Forms\Components\TextInput::make('powierzchnia')
                    ->label('Powierzchnia')
                    ->numeric(),
                Forms\Components\TextInput::make('referencje')
                    ->label('Referencje'),
                Forms\Components\Textarea::make('stats')
                    ->label('Stats'),
                Forms\Components\TextInput::make('old_id')
                    ->label('Old ID')
                    ->numeric(),
                Forms\Components\TextInput::make('hits')
                    ->label('Hits')
                    ->numeric(),
                Forms\Components\TextInput::make('weight')
                    ->label('Weight')
                    ->numeric(),
                Forms\Components\TextInput::make('weightold')
                    ->label('Weight Old')
                    ->numeric(),
                Forms\Components\TextInput::make('pierwotna_waga_przed_zmianą_na_standard')
                    ->label('Pierwotna Waga Przed Zmianą na Standard')
                    ->numeric(),
                Forms\Components\TextInput::make('portal')
                    ->label('Portal'),
            ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Title'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created')
                    ->label('Created')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated')
                    ->label('Updated')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('cena')
                    ->label('Cena')
                    ->numeric(),
                Tables\Columns\TextColumn::make('powierzchnia')
                    ->label('Powierzchnia')
                    ->numeric(),
                Tables\Columns\TextColumn::make('hits')
                    ->label('Hits')
                    ->numeric(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight')
                    ->numeric(),
            ])
            ->filters([
                // Dodaj filtry, jeśli są potrzebne
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListNotices::route('/'),
            'create' => Pages\CreateNotice::route('/create'),
            'edit' => Pages\EditNotice::route('/{record}/edit'),
        ];
    }

}
