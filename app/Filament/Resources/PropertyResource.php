<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\PropertyResource\RelationManagers;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getRelations(): array
    {
        return [];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
             //rodaj transakcji
             //przedmiot ogloszenia
             //uzytkownik?
            
                Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->label('URL')
                    ->required(),
                Forms\Components\TextInput::make('cena')
                    ->label('Cena')
                    ->numeric(),
                Forms\Components\TextInput::make('powierzchnia')
                    ->label('Powierzchnia')
                    ->numeric(),
                Forms\Components\TextInput::make('referencje')
                    ->label('Referencje'),
                Forms\Components\TextInput::make('promote')
                    ->label('Promote')
                    ->numeric(),
                Forms\Components\RichEditor::make('body')
                    ->label('Body')
                    ->required()
                    ->columnSpan('full'),


                    TextInput::make('miejscowosc')
                    ->label('Miejscowość')
                    ->id('autocomplete'),
                Forms\Components\ViewField::make('map')
                    ->label('Mapa')
                    ->view('filament.resources.property-resource.map', [
                        'latitude' => function($record) {
                            return $record->teryt ? $record->teryt->latitude : 52.2297;
                        },
                        'longitude' => function($record) {
                            return $record->teryt ? $record->teryt->longitude : 21.0122;
                        },
                    ]),
                Fieldset::make('Dane terytorialne')
                    ->relationship('teryt')
                    ->schema([
                        TextInput::make('latitude')
                            ->label('Szerokość geograficzna')
                            ->numeric()
                            ->default(fn($record) => $record->teryt ? $record->teryt->latitude : 52.2297),
                        TextInput::make('longitude')
                            ->label('Długość geograficzna')
                            ->numeric()
                            ->default(fn($record) => $record->teryt ? $record->teryt->longitude : 21.0122),

                        TextInput::make('wojewodztwo')
                            ->label('Województwo')
                            ->default(fn($record) => $record->teryt ? $record->teryt->wojewodztwo : ''),
                        TextInput::make('powiat')
                            ->label('Powiat')
                            ->default(fn($record) => $record->teryt ? $record->teryt->powiat : ''),
                        TextInput::make('gmina')
                            ->label('Gmina')
                            ->default(fn($record) => $record->teryt ? $record->teryt->gmina : ''),
                        TextInput::make('ulica')
                            ->label('Ulica')
                            ->default(fn($record) => $record->teryt ? $record->teryt->ulica : ''),
                    ]),
                            

                 FileUpload::make('images')
                                ->label('Zdjęcia')
                                ->multiple()
                                ->directory('property-images')
                                ->preserveFilenames()
                                ->image()
                                ->maxSize(5120)
                                ->enableReordering()
                                ->enableDownload()
                                ->enableOpen()
                                ->columnSpan('full'),


                Fieldset::make('Premium')
                                ->relationship('premium')
                                ->schema([
                                    TextInput::make('premium_id')
                                        ->label('Premium ID')
                                        ->default(fn($record) => $record->premium ? $record->premium->premium_id : 1),
                                    DatePicker::make('datefrom')
                                        ->label('Data od')
                                        ->default(fn($record) => $record->premium ? $record->premium->datefrom : ''),
                                    DatePicker::make('dateto')
                                        ->label('Data do')
                                        ->default(fn($record) => $record->premium ? $record->premium->dateto : ''),
                                    TextInput::make('platnosc_premium')
                                        ->label('RAZEM')
                                        ->default(fn($record) => $record->premium ? $record->premium->platnosc_premium : 1),
                                    TextInput::make('platnosc_total')
                                        ->label('OGŁOSZENIE NIE WYRÓŻNIONE STANDARD: *')
                                        ->default(fn($record) => $record->premium ? $record->premium->platnosc_total : 1),
                                    Toggle::make('regulamin')
                                        ->label('Regulamin'),
                                    Toggle::make('paid')
                                        ->label('Opłacone'),
                                ]),

                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->required(),
                Forms\Components\TextInput::make('terms')
                    ->label('Terms'),

                Forms\Components\TextInput::make('lft')
                    ->label('Left')
                    ->numeric(),
                Forms\Components\TextInput::make('rght')
                    ->label('Right')
                    ->numeric(),

                Forms\Components\TextInput::make('type')
                    ->label('Type')
                    ->required(),
                Forms\Components\DateTimePicker::make('updated')
                    ->label('Updated')
                    ->required(),
                Forms\Components\DateTimePicker::make('created')
                    ->label('Created')
                    ->required(),
                Forms\Components\TextInput::make('portal')
                    ->label('Portal'),
            ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('first_image')
                ->label('Zdjęcie')
                ->getStateUsing(fn($record) => $record->getFirstImage()), // Pobranie URL obrazu

                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                    Tables\Columns\TextColumn::make('created')
                    ->label('Opublikowano')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Tytuł'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
                    Tables\Columns\TextColumn::make('promote')
                    ->label('Premium'),
                    Tables\Columns\TextColumn::make('powierzchnia')
                    ->label('Miejscowość')
                    ->numeric(),
                Tables\Columns\TextColumn::make('cena')
                    ->label('Cena')
                    ->numeric(),
                Tables\Columns\TextColumn::make('teryt.wojewodztwo')
                    ->label('Województwo'),

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
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }

}
