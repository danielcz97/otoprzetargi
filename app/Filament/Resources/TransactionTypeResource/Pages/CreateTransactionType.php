<?php

namespace App\Filament\Resources\TransactionTypeResource\Pages;

use App\Filament\Resources\TransactionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionType extends CreateRecord
{
    protected static string $resource = TransactionTypeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (request()->has('model_type')) {
            $data['model_type'] = request()->query('model_type');
        }
        return $data;
    }
}
