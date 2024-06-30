<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;

    protected static ?string $title = 'Tambah Daftar Bahan';

    public function form(Form $form): Form
    {
        return MaterialResource::createForm($form);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
