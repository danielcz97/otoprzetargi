<?php

namespace App\Filament\Resources\PropertyResource\Pages;

use App\Filament\Resources\PropertyResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use App\Models\ObjectType;
use App\Models\TransactionType;

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

    public $autocomplete;

    public function mount($record): void
    {
        parent::mount($record);

        $this->autocomplete = $this->record->miejscowosc ?? '';
    }

    private function getTransactionTypeLabel($id): ?string
    {
        $transactionType = TransactionType::find($id);
        return $transactionType ? $transactionType->name : null;
    }

    private function getObjectTypeLabel($id): ?string
    {
        $objectType = ObjectType::find($id);
        return $objectType ? $objectType->name : null;
    }
}
