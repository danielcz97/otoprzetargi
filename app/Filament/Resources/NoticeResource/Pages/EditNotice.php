<?php

namespace App\Filament\Resources\NoticeResource\Pages;

use App\Filament\Resources\NoticeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotice extends EditRecord
{
    protected static string $resource = NoticeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('podglad')
                ->label('Podgląd')
                ->url(fn() => url('komunikaty/' . $this->record->slug))
                ->openUrlInNewTab(),
        ];
    }
    public $autocomplete;

    public function mount($record): void
    {
        parent::mount($record);

        $this->autocomplete = $this->record->miejscowosc ?? '';
    }

}
