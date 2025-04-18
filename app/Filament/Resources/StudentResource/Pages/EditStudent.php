<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan data user terhubung
        if (isset($data['user'])) {
            $this->record->user->update($data['user']);
            unset($data['user']);
        }

        return $data;
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan relasi user dimuat
        $this->record->loadMissing('user');
    }

    protected function fillForm(): void
    {
        // Ambil data student + data relasi user dan set ke form
        $this->form->fill([
            ...$this->record->attributesToArray(),
            'user' => $this->record->user?->only(['name', 'email']), // isi field relasi
        ]);
    }
}
