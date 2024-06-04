<?php

namespace App\Filament\Resources\ClaimResource\Pages;

use App\Filament\Resources\ClaimResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClaim extends CreateRecord
{
    protected static string $resource = ClaimResource::class;

    public $autocomplete;

    public function mount(): void
    {
        parent::mount();

        $this->autocomplete = $this->record->miejscowosc ?? '';
    }
}
