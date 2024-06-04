<?php

namespace App\Filament\Resources\MovablePropertyResource\Pages;

use App\Filament\Resources\MovablePropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMovableProperty extends CreateRecord
{
    protected static string $resource = MovablePropertyResource::class;

    public $autocomplete;

    public function mount(): void
    {
        parent::mount();

        $this->autocomplete = $this->record->miejscowosc ?? '';
    }
}
