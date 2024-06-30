<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected static ?string $title = 'Edit Data Bahan Produksi';

    public function form(Form $form): Form
    {
        return MaterialResource::editForm($form);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
