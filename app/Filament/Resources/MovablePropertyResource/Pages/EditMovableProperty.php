<?php

namespace App\Filament\Resources\MovablePropertyResource\Pages;

use App\Filament\Resources\MovablePropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovableProperty extends EditRecord
{
    protected static string $resource = MovablePropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function fillForm(): void
    {
        parent::fillForm();

        $terms = json_decode($this->record->terms, true);
        $this->form->fill([
            'transaction_type' => $terms ? array_key_first($terms) : [],
            'object_type' => $terms ? array_key_last($terms) : [],
            'title' => $this->record->title,
            'slug' => $this->record->slug,
            'cena' => $this->record->cena,
            'powierzchnia' => $this->record->powierzchnia,
            'referencje' => $this->record->referencje,
            'promote' => $this->record->promote,
            'body' => $this->record->body,
            'miejscowosc' => $this->record->miejscowosc,
            'status' => $this->record->status,
            'terms' => $this->record->terms,
            'lft' => $this->record->lft,
            'rght' => $this->record->rght,
            'type' => $this->record->type,
            'updated' => $this->record->updated,
            'created' => $this->record->created,
            'portal' => $this->record->portal,
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['terms'] = json_encode([
            $data['transaction_type'] => $this->getTransactionTypeLabel($data['transaction_type']),
            $data['object_type'] => $this->getObjectTypeLabel($data['object_type']),
        ]);

        unset($data['transaction_type']);
        unset($data['object_type']);

        return $data;
    }

    private function getTransactionTypeLabel($value)
    {
        $options = [
            '10' => 'sprzedaz',
            '11' => 'kupno',
            '13' => 'wynajem',
            '12' => 'dzierzawa',
            '5' => 'inne',
        ];

        return $options[$value] ?? '';
    }

    private function getObjectTypeLabel($value)
    {
        $options = [
            '32' => 'samochody osobowe',
            '33' => 'samochody ciężarowe',
            '34' => 'pojazdy specjalistyczne',
            '35' => 'maszyny, urządzenia',
            '47' => 'łodzie',
            '48' => 'maszyny przemysłowe',
            '49' => 'maszyny rolnicze',
            '50' => 'przyczepy/naczepy',
            '51' => 'motocykle/skutery',
        ];

        return $options[$value] ?? '';
    }
}
