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
    public $autocomplete;

    public function mount($record): void
    {
        parent::mount($record);

        $this->autocomplete = $this->record->miejscowosc ?? '';
    }
}
