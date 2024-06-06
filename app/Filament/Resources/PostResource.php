<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Premium;
use App\Models\Property;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $slug = Str::slug(
                            str_replace(
                                ['ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż', 'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż'],
                                ['a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', 'A', 'C', 'E', 'L', 'N', 'O', 'S', 'Z', 'Z'],
                                $state
                            )
                        );
                        $set('slug', $slug);
                    }),
                TextInput::make('slug')
                    ->label('URL')
                    ->required()
                    ->afterStateUpdated(function (Forms\Set $set, $state, $record) {
                        $slug = Str::slug($state);
                        $originalSlug = $slug;
                        $i = 1;
                        while (Property::where('slug', $slug)->exists()) {
                            $slug = $originalSlug . '-' . $i++;
                        }
                        $set('slug', $slug);
                    }),
                FileUpload::make('attachment')
                    ->label('Zdjęcie'),

                Forms\Components\RichEditor::make('body')
                    ->label('Body')
                    ->required()
                    ->columnSpan('full'),

                Forms\Components\DateTimePicker::make('updated')
                    ->label('Data aktualizacji')
                    ->default('now')
                    ->required(),
                Forms\Components\DateTimePicker::make('created')
                    ->label('Data utworzenia')
                    ->default('now')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('created')
                    ->label('Opublikowano')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tytuł'),

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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Nowości';
    }

}
