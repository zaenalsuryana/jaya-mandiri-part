<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use App\Filament\Resources\PelangganResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePelanggan extends CreateRecord
{
    protected static string $resource = PelangganResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate kode_pelanggan secara otomatis: CUS-00001, Dst.
        $count = \App\Models\Pelanggan::count() + 1;
        $data['kode_pelanggan'] = 'CUS-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        return $data;
    }
}
