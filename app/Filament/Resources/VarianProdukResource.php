<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VarianProdukResource\Pages;
use App\Models\VarianProduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VarianProdukResource extends Resource
{
    protected static ?string $model = VarianProduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationGroup = 'Manajemen Produk';
    protected static ?string $modelLabel = 'Varian Produk (SKU)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi SKU & Varian')
                    ->schema([
                        Forms\Components\Select::make('produk_id')
                            ->relationship('produk', 'nama_produk')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Pilih Produk Induk'),
                        Forms\Components\TextInput::make('nama_varian')
                            ->maxLength(255)
                            ->label('Nama Varian (Opsional)')
                            ->placeholder('Misal: Warna Merah, Ukuran XL'),
                        Forms\Components\TextInput::make('sku')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->label('SKU / Kode Barcode'),
                        Forms\Components\TextInput::make('stok')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->label('Stok Saat Ini'),
                    ])->columns(2),
                
                Forms\Components\Section::make('Informasi Harga')
                    ->schema([
                        Forms\Components\TextInput::make('harga_beli')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(1000)
                            ->prefix('Rp')
                            ->placeholder('Misal: 150000'),
                        Forms\Components\TextInput::make('harga_jual')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step(1000)
                            ->prefix('Rp')
                            ->placeholder('Misal: 200000'),
                        Forms\Components\TextInput::make('harga_grosir')
                            ->numeric()
                            ->minValue(0)
                            ->step(1000)
                            ->prefix('Rp')
                            ->label('Harga Grosir (Opsional)')
                            ->placeholder('Misal: 190000'),
                    ])->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->searchable()
                    ->sortable()
                    ->label('Produk Induk'),
                Tables\Columns\TextColumn::make('nama_varian')
                    ->searchable()
                    ->label('Varian')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->copyable()
                    ->label('SKU / Barcode'),
                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state <= 5 => 'danger',
                        $state <= 20 => 'warning',
                        default => 'success',
                    })
                    ->label('Stok'),
                Tables\Columns\TextColumn::make('harga_jual')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->label('Harga Jual'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVarianProduks::route('/'),
            'create' => Pages\CreateVarianProduk::route('/create'),
            'edit' => Pages\EditVarianProduk::route('/{record}/edit'),
        ];
    }
}