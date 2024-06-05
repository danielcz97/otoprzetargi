<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovablePropertyResource\Pages;
use App\Models\MovableProperty;

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
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Str;
use Filament\Forms\Components\Hidden;
use App\Models\ObjectType;
use App\Models\TransactionType;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Navigation\NavigationItem;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Filament\Forms\Components\CheckboxList;

class MovablePropertyResource extends Resource
{
    protected static ?string $model = MovableProperty::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('transaction_type')
                    ->label('Przedmiot ogłoszenia')
                    ->options(fn() => TransactionType::where('model_type', 'App\\Models\\MovableProperty')->pluck('name', 'id')->toArray())
                    ->required()
                    ->default(fn($record) => $record?->transaction_type)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $terms = $get('terms');
                        $termsArray = is_array($terms) ? $terms : (is_string($terms) ? json_decode($terms, true) : []);
                        $transactionTypeName = TransactionType::find($state)?->name;
                        if ($transactionTypeName) {
                            $termsArray[$state] = $transactionTypeName;
                        }
                        $set('terms', json_encode($termsArray));
                    }),

                Select::make('object_type')
                    ->label('Rodzaj obiektu')
                    ->options(fn() => ObjectType::where('model_type', 'App\\Models\\MovableProperty')->pluck('name', 'id')->toArray())
                    ->required()
                    ->default(fn($record) => $record?->object_type)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $terms = $get('terms');
                        $termsArray = is_array($terms) ? $terms : (is_string($terms) ? json_decode($terms, true) : []);
                        $objectTypeName = ObjectType::find($state)?->name;
                        if ($objectTypeName) {
                            $termsArray[$state] = $objectTypeName;
                        }
                        $set('terms', json_encode($termsArray));
                    }),

                Hidden::make('terms')
                    ->default(fn($record) => $record ? json_encode($record->terms) : null),


                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->live()
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
                Forms\Components\TextInput::make('cena')
                    ->label('Cena')
                    ->numeric(),
                Forms\Components\TextInput::make('powierzchnia')
                    ->label('Powierzchnia')
                    ->numeric(),
                Select::make('contact_id')
                    ->label('Kontakt')
                    ->relationship('contact', 'nazwa')
                    ->searchable(),
                Forms\Components\TextInput::make('views')
                    ->label('Ilość wyświetleń')
                    ->numeric()
                    ->default(0),
                Forms\Components\RichEditor::make('body')
                    ->label('Body')
                    ->required()
                    ->columnSpan('full'),
                Fieldset::make('Dane terytorialne')
                    ->relationship('teryt')
                    ->schema([
                        Forms\Components\TextInput::make('miejscowosc')
                            ->label('Miejscowość')
                            ->id('autocomplete'),

                        Map::make('location')
                            ->label('Mapa')
                            ->mapControls([
                                'mapTypeControl' => true,
                                'scaleControl' => true,
                                'streetViewControl' => true,
                                'rotateControl' => true,
                                'fullscreenControl' => true,
                                'searchBoxControl' => false,
                                'zoomControl' => false,
                            ])
                            ->height(fn() => '400px')
                            ->defaultZoom(10)
                            ->autocomplete(
                                fieldName: 'miejscowosc',
                                types: ['(cities)'],
                                countries: ['PL']
                            )
                            ->autocompleteReverse(true)
                            ->reverseGeocode([
                                'street' => '%n %S',
                                'city' => '%L',
                                'state' => '%A1',
                                'zip' => '%z',
                            ])
                            ->defaultLocation([52.2297, 21.0122]) // Warszawa jako domyślna lokalizacja
                            ->draggable()
                            ->clickable(false)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            }),
                        Forms\Components\TextInput::make('latitude')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('location', [
                                    'lat' => floatVal($state),
                                    'lng' => floatVal($get('longitude')),
                                ]);
                            })
                            ->lazy(), // important to use lazy, to avoid updates as you type
                        Forms\Components\TextInput::make('longitude')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $set('location', [
                                    'lat' => floatval($get('latitude')),
                                    'lng' => floatVal($state),
                                ]);
                            })
                            ->lazy(),
                        Forms\Components\TextInput::make('wojewodztwo')
                            ->label('Województwo')
                            ->default(fn($record) => $record->teryt->wojewodztwo ?? ''),
                        Forms\Components\TextInput::make('miasto')
                            ->label('Miasto')
                            ->default(fn($record) => $record->teryt->miasto ?? 'brak'),
                        Forms\Components\TextInput::make('powiat')
                            ->label('Powiat')
                            ->default(fn($record) => $record->teryt->powiat ?? ''),
                        Forms\Components\TextInput::make('gmina')
                            ->label('Gmina')
                            ->default(fn($record) => $record->teryt->gmina ?? ''),
                        Forms\Components\TextInput::make('ulica')
                            ->label('Ulica')
                            ->default(fn($record) => $record->teryt->ulica ?? ''),
                    ]),
                SpatieMediaLibraryFileUpload::make('media')
                    ->collection('default')
                    ->multiple()
                    ->reorderable(),
                CheckboxList::make('portal')
                    ->label('Wyświetlane w serwisie')
                    ->options([
                        'Wierzytelności' => 'Wierzytelności',
                        'GC Trader' => 'GC Trader',
                        'Otoprzetargi' => 'Otoprzetargi',
                        'Syndycy' => 'Syndycy'
                    ])
                    ->columns(2),
                Fieldset::make('Premium')
                    ->relationship('premium')
                    ->schema([
                        Select::make('premium_id')
                            ->label('Rodzaj Premium')
                            ->options(Premium::all()->pluck('title', 'id')->toArray()) // Zmieniono z Premiums na Premium
                            ->default(fn($record) => $record->premium->premium_id ?? 1),
                        DatePicker::make('datefrom')
                            ->label('Data od')
                            ->default(fn($record) => $record->premium->datefrom ?? 'today'),
                        DatePicker::make('dateto')
                            ->label('Data do')
                            ->default(fn($record) => $record->premium->dateto ?? 'today + 1month'),
                        TextInput::make('platnosc_premium')
                            ->label('RAZEM')
                            ->default(fn($record) => $record->premium->platnosc_premium ?? 1),
                    ]),

                Forms\Components\DateTimePicker::make('updated')
                    ->label('Data aktualizacji')
                    ->default('now')
                    ->required(),
                Forms\Components\DateTimePicker::make('created')
                    ->label('Data utworzenia')
                    ->default('now')
                    ->required(),

                Forms\Components\Toggle::make('cyclic')
                    ->label('Cykliczne ogłoszenie'),
                Forms\Components\TextInput::make('cyclic_day')
                    ->label('Dzień dodawania')
                    ->numeric()
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
                Tables\Columns\TextColumn::make('views')
                    ->label('Ilość wyświetleń')
                    ->numeric(),
                Tables\Columns\TextColumn::make('cena')
                    ->label('Cena')
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

    public static function getNavigationLabel(): string
    {
        return 'Ruchomości';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovableProperties::route('/'),
            'create' => Pages\CreateMovableProperty::route('/create'),
            'edit' => Pages\EditMovableProperty::route('/{record}/edit'),
        ];
    }
    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Ruchomości')
                ->url(static::getUrl('index'))
                ->icon(static::$navigationIcon)
                ->group('Ruchomości'),
            NavigationItem::make('Typy obiektów')
                ->url(ObjectTypeResource::getUrl('index', ['model_type' => 'App\\Models\\MovableProperty']))
                ->icon('heroicon-o-rectangle-stack')
                ->group('Ruchomości'),
            NavigationItem::make('Typy transakcji')
                ->url(TransactionTypeResource::getUrl('index', ['model_type' => 'App\\Models\\MovableProperty']))
                ->icon('heroicon-o-rectangle-stack')
                ->group('Ruchomości'),
        ];
    }
}
