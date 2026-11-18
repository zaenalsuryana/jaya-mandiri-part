<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Models\Purchase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $modelLabel = 'Pembelian Stok (PO)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembelian')
                    ->schema([
                        Forms\Components\Select::make('pemasok_id')
                            ->relationship('pemasok', 'nama')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Pemasok / Supplier'),
                        Forms\Components\DatePicker::make('tanggal_beli')
                            ->required()
                            ->default(now())
                            ->label('Tanggal Pembelian'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'PENDING' => 'Menunggu',
                                'RECEIVED' => 'Diterima /Selesai',
                                'CANCELLED' => 'Dibatalkan',
                            ])
                            ->required()
                            ->default('RECEIVED')
                            ->label('Status Pembelian'),
                        Forms\Components\TextInput::make('total_biaya')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->required()
                            ->label('Total Biaya'),
                    ])->columns(2),

                Forms\Components\Section::make('Daftar Item Produk')
                    ->schema([
                        Forms\Components\Repeater::make('purchaseItems')
                            ->relationship('purchaseItems')
                            ->schema([
                                Forms\Components\Select::make('produk_id')
                                    ->relationship('produk', 'nama')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Produk'),

                                Forms\Components\TextInput::make('jumlah')
                                    ->numeric()
                                    ->required()
                                    ->default(1)
                                    ->label('Jumlah (Qty)'),

                                Forms\Components\TextInput::make('harga_beli')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->label('Harga Beli Satuan')
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->label('Item Barang'),
                    ]),

                Forms\Components\Section::make('Catatan Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('catatan')
                            ->columnSpanFull()
                            ->placeholder('Catatan atau nomor nota dari suplier...'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_purchase')
                    ->searchable()
                    ->sortable()
                    ->label('No. PO'),
                Tables\Columns\TextColumn::make('pemasok.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Pemasok'),
                Tables\Columns\TextColumn::make('tanggal_beli')
                    ->searchable()
                    ->sortable()
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'RECEIVED' => 'success',
                        'PENDING' => 'warning',
                        'CANCELLED' => 'danger',
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}
