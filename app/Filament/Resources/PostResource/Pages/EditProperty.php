<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Resources\Pages\EditRecord;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    protected function fillForm(): void
    {
        parent::fillForm();

        $terms = json_decode($this->record->terms, true);
        $this->form->fill([
            'title' => $this->record->title,
            'slug' => $this->record->slug,
            'body' => $this->record->body,
            'updated' => $this->record->updated,
            'created' => $this->record->created,
        ]);
    }
}
