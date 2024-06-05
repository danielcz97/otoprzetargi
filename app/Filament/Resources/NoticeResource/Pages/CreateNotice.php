<?php

namespace App\Filament\Resources\NoticeResource\Pages;

use App\Filament\Resources\NoticeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNotice extends CreateRecord
{
    protected static string $resource = NoticeResource::class;

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
