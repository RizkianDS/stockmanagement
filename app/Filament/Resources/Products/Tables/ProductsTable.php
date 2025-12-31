<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->sortable()
                    ->searchable()
                    ->disabledClick(),
                TextColumn::make('type')
                    ->label('Jenis Produk')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'food' => 'Makanan',
                            'drink' => 'Minuman',
                            default => $state,
                        };
                    })
                    ->sortable()
                    ->searchable()
                    ->disabledClick(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->sortable()
                    ->searchable()
                    ->disabledClick(),
                TextColumn::make('quantity')
                    ->label('Kuantitas')
                    ->sortable()
                    ->searchable()
                    ->disabledClick(),
            ])
            ->recordAction(null)
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->icon('heroicon-o-eye'),
                    EditAction::make()
                        ->icon('heroicon-o-pencil-square'),
                    DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ])
                    ->tooltip('Aksi')
                    ->label('Aksi'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
