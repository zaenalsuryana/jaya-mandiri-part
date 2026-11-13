<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $modelLabel = 'Pelanggan & Bengkel';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama Pelanggan atau Bengkel'),
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'RETAIL' => 'Perorangan (Retail)',
                                'WORKSHOP' => 'Bengkel (Workshop)',
                            ])
                            ->required()
                            ->default('RETAIL')
                            ->label('Tipe Pelanggan'),
                        Forms\Components\TextInput::make('telepon')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Pengaturan Harga & Status')
                    ->schema([
                        Forms\Components\Select::make('tingkat_harga')
                            ->options([
                                'RETAIL' => 'Harga Eceran (Retail)',
                                'WHOLESALE' => 'Harga Grosir (Wholesale)',
                                'CUSTOM' => 'Harga Khusus (Custom)',
                            ])
                            ->required()
                            ->default('RETAIL')
                            ->label('Tingkat Harga'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'PENDING' => 'Menunggu Persetujuan',
                                'ACTIVE' => 'Aktif',
                                'SUSPENDED' => 'Ditangguhkan',
                            ])
                            ->required()
                            ->default('ACTIVE'),
                        Forms\Components\Toggle::make('is_default')
                            ->label('Jadikan "Pelanggan Umum" Bawaan Kasir')
                            ->default(false),
                    ])->columns(3),

                Forms\Components\Section::make('Data Kredit & Tagihan (Khusus Bengkel)')
                    ->schema([
                        Forms\Components\TextInput::make('lama_tempo')
                            ->numeric()
                            ->default(0)
                            ->suffix('Hari')
                            ->label('Lama Tempo Bayar'),
                        Forms\Components\TextInput::make('batas_kredit')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->step(10000)
                            ->label('Batas Maksimal Kasbon / Kredit'),
                        Forms\Components\TextInput::make('npwp')
                            ->maxLength(255)
                            ->label('Nomor NPWP (Opsional)'),
                    ])->columns(3),

                Forms\Components\Section::make('Catatan Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')
                            ->columnSpanFull()
                            ->placeholder('Tambahkan catatan internal mengenai pelanggan ini...'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_pelanggan')
                    ->searchable()
                    ->sortable()
                    ->label('Kode'),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'WORKSHOP' => 'info',
                        'RETAIL' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('telepon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tingkat_harga')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'WHOLESALE' => 'warning',
                        'CUSTOM' => 'danger',
                        default => 'gray',
                    })
                    ->label('Harga'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ACTIVE' => 'success',
                        'PENDING' => 'warning',
                        'SUSPENDED' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}