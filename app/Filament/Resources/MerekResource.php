<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerekResource\Pages;
use App\Filament\Resources\MerekResource\RelationManagers;
use App\Models\Merek;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MerekResource extends Resource
{
    protected static ?string $model = Merek::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_merek')
                    ->label('Nama Merek')
                    ->required()
                    ->maxLength(255),
                Select::make('kualitas')
                    ->label('Kualitas Merek')
                    ->options([
                        'ASLI' => 'ASLI',
                        'OEM' => 'OEM',
                        'AFTERMARKET' => 'AFTERMARKET',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_merek')
                    ->label('Nama Merek')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kualitas')
                    ->label('Kualitas')
                    ->badge()
                    ->colors([
                        'success' => 'ASLI',
                        'warning' => 'OEM',
                        'info' => 'AFTERMARKET',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
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
            'index' => Pages\ListMereks::route('/'),
            'create' => Pages\CreateMerek::route('/create'),
            'edit' => Pages\EditMerek::route('/{record}/edit'),
        ];
    }
}