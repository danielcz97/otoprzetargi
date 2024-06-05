<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    public $autocomplete;

    public function mount(): void
    {
        parent::mount();

        $this->autocomplete = $this->record->miejscowosc ?? '';
    }

    protected function afterSave(): void
    {
        $this->notify('success', 'Rekord został utworzony. <a href="' . url('komunikaty/' . $this->record->slug) . '" target="_blank">Przejdź do strony</a>');
    }
}
