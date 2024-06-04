<?php

namespace App\Filament\Resources\TransactionTypeResource\Pages;

use App\Filament\Resources\TransactionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTransactionTypes extends ListRecords
{
    protected static string $resource = TransactionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn() => static::getResource()::getUrl('create', ['model_type' => request()->query('model_type')])),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        return parent::getTableQuery()
            ->where('model_type', request()->query('model_type'));
    }
}
