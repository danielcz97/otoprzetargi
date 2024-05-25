<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Premium;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getRelations(): array
    {
        return [];
    }
    public static function query(): Builder
    {
        return parent::query()->orderBy('id', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('transaction_type')
                    ->label('Rodzaj transakcji')
                    ->options([
                        '10' => 'Sprzedaż',
                        '11' => 'Kupno',
                        '13' => 'Wynajem',
                        '12' => 'Dzierżawa',
                        '5' => 'Inne',
                    ])
                    ->required()
                    ->default(fn($record) => $record?->transaction_type), // Pobieranie bieżącej wartości z modelu

                Select::make('object_type')
                    ->label('Rodzaj obiektu')
                    ->options([
                        '22' => 'biuro/obiekt biurowy',
                        '23' => 'dom',
                        '25' => 'dworek/pałac',
                        '26' => 'działka',
                        '27' => 'hotel/pensjonat',
                        '28' => 'lokal użytkowy',
                        '29' => 'magazyn',
                        '30' => 'mieszkanie',
                        '31' => 'obiekt użytkowy',
                    ])
                    ->required()
                    ->default(fn($record) => $record?->object_type), // Pobieranie bieżącej wartości z modelu

                TextInput::make('title')
                    ->label('Title')
                    ->required()
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
                // Forms\Components\TextInput::make('referencje')
                //     ->label('Referencje'),
                // Forms\Components\TextInput::make('promote')
                //     ->label('Promote')
                //     ->numeric(),
                Forms\Components\RichEditor::make('body')
                    ->label('Body')
                    ->required()
                    ->columnSpan('full'),
                Forms\Components\TextInput::make('miejscowosc')
                    ->label('Miejscowość')
                    ->id('autocomplete'),
                Forms\Components\ViewField::make('map')
                    ->label('Mapa')
                    ->view('filament.resources.property-resource.map', [
                        'latitude' => fn($record) => $record->teryt->latitude ?? 52.2297,
                        'longitude' => fn($record) => $record->teryt->longitude ?? 21.0122,
                    ]),
                Fieldset::make('Dane terytorialne')
                    ->relationship('teryt')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('Szerokość geograficzna')
                            ->numeric()
                            ->default(fn($record) => $record->teryt->latitude ?? 52.2297),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Długość geograficzna')
                            ->numeric()
                            ->default(fn($record) => $record->teryt->longitude ?? 21.0122),
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
                FileUpload::make('images')
                    ->label('Zdjęcia')
                    ->multiple()
                    ->disk('public')
                    ->directory('property-images')
                    ->preserveFilenames()
                    ->image()
                    ->maxSize(5120)
                    ->reorderable()
                    ->downloadable()
                    ->openable()
                    ->columnSpan('full')
                    ->imagePreviewHeight('250')
                    ->panelLayout('integrated')
                    ->reorderable(),
                Fieldset::make('Premium')
                    ->relationship('premium')
                    ->schema([
                        Select::make('premium_id')
                            ->label('Rodzaj Premium')
                            ->options(Premium::all()->pluck('title', 'id')->toArray()) // Zmieniono z Premiums na Premium
                            ->default(fn($record) => $record->premium->premium_id ?? 1),
                        DatePicker::make('datefrom')
                            ->label('Data od')
                            ->default(fn($record) => $record->premium->datefrom ?? ''),
                        DatePicker::make('dateto')
                            ->label('Data do')
                            ->default(fn($record) => $record->premium->dateto ?? ''),
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

    public static function getNavigationLabel(): string
    {
        return 'Nieruchomości';
    }
}
