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

    protected function afterSave()
    {
        $this->notify('success', 'Nieruchomość została zaktualizowana. <a href="' . route('properties.index', $this->record->slug) . '">Zobacz podgląd</a>');
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
            '22' => 'biuro/obiekt biurowy',
            '23' => 'dom',
            '25' => 'dworek/pałac',
            '26' => 'działka',
            '27' => 'hotel/pensjonat',
            '28' => 'lokal użytkowy',
            '29' => 'magazyn',
            '30' => 'mieszkanie',
            '31' => 'obiekt użytkowy',
        ];

        return $options[$value] ?? '';
    }
}
