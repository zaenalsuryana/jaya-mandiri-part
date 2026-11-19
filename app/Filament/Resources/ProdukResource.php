<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Katalog';
    protected static ?string $modelLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Produk')
                    ->schema([
                        TextInput::make('nama_produk')
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Sluge (URL)')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->relationship('kategori', 'nama_kategori')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Select::make('merek_id')
                            ->label('Merek')
                            ->relationship('merek', 'nama_merek')
                            ->required()
                            ->searchable()
                            ->preload(),

                        TextInput::make('nomor_part')
                            ->label('Nomor Prt / OEM')
                            ->maxLength(60),

                        Select::make('status')
                            ->label('Status Produk')
                            ->options([
                                'DRAFT' => 'Draft',
                                'ACTIVE' => 'Active',
                                'INACTIVE' => 'Inactive',
                            ])
                            ->default('ACTIVE')
                            ->required(),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Produk')
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Section::make('Varian & Harga Proguk (SKU)')
                    ->schema([
                        Repeater::make('varianProduks')
                            ->relationship('varianProduks')
                            ->schema([
                                TextInput::make('nama_varian')
                                    ->label('Nama Varian')
                                    ->default('standar')
                                    ->required(),

                                TextInput::make('sku')
                                    ->label('Kode SKU')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                TextInput::make('stok')
                                    ->label('Stok')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                TextInput::make('harga_beli')
                                    ->label('Harga Modal / Beli')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->required(),

                                TextInput::make('harga_jual')
                                    ->label('Harga Ecer / Jual')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->required(),

                                TextInput::make('harga_grosir')
                                    ->label('Harga Grosir (Bengkel)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->defaultItems(1)
                            ->label('Daftar Varian (SKU)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_produk')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('merek.nama_merek')
                    ->label('Merek')
                    ->sortable(),
                TextColumn::make('nomor_part')
                    ->label('Nomor Part')
                    ->searchable(),
                TextColumn::make('varian_produks_count')
                    ->counts('varianProduks')
                    ->label('Varian (SKU)'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'ACTIVE',
                        'warning' => 'DRAFT',
                        'danger' => 'INACTIVE',
                    ]),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}