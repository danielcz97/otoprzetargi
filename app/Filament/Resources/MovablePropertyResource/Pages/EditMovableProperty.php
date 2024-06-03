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
            Actions\Action::make('podglad')
                ->label('Podgląd')
                ->url(fn() => url('ruchomosci/' . $this->record->slug))
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
