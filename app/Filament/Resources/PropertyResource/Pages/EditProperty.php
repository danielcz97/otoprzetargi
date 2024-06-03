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
