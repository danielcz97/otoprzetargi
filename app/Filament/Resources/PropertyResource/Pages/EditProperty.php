<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('podglad')
                ->label('Podgląd')
                ->url(fn() => url('nieruchomosci/' . $this->record->slug))
                ->openUrlInNewTab(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['terms']) && is_array($data['terms'])) {
            $data['terms'] = json_encode($data['terms']);
        }

        return $data;
    }

    public $autocomplete;

    public function mount($record): void
    {
        parent::mount($record);

        $this->autocomplete = $this->record->miejscowosc ?? '';
    }
}
