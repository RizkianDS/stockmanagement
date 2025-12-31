<?php

namespace App\Filament\Resources\Products\Pages;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Products\ProductResource;
use Filament\Notifications\Notification;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('global_stock_in')
                ->label('Stok Masuk')
                ->color('success')
                ->icon('heroicon-o-plus')
                ->modalHeading('Stok Masuk Produk')
                ->form([
                    Select::make('product_id')
                        ->label('Pilih Produk')
                        ->options(Product::pluck('name', 'id')) // Mengambil daftar produk
                        ->required()
                        ->searchable(), // Memudahkan pencarian produk

                    TextInput::make('amount')
                        ->label('Jumlah Masuk')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->minValue(1),
                ])
                ->action(function (array $data) {
                    $product = Product::find($data['product_id']);

                    if ($product) {
                        $product->quantity += $data['amount'];
                        $product->save();
                        Notification::make()
                            ->title('Stok berhasil ditambahkan.')
                            ->body('Stok produk ' . $product->name . ' telah bertambah sebanyak ' . $data['amount'] . '.')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Gagal!')
                            ->body('Produk tidak ditemukan.')
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('global_stock_out')
                ->label('Stok Keluar')
                ->color('danger')
                ->icon('heroicon-o-minus')
                ->modalHeading('Stok Keluar Produk')
                ->form([
                    Select::make('product_id')
                        ->label('Pilih Produk')
                        ->options(Product::pluck('name', 'id'))
                        ->required()
                        ->searchable(),

                    TextInput::make('amount')
                        ->label('Jumlah Keluar')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->minValue(1)
                ])
                ->action(function (array $data) {
                    $product = Product::find($data['product_id']);
                    $amount = $data['amount'];

                    if (!$product) {
                        Notification::make()
                            ->title('Gagal!')
                            ->body('Produk tidak ditemukan.')
                            ->danger()
                            ->send();
                        return;
                    }

                    if ($amount > $product->quantity) {
                        Notification::make()
                            ->title('Validasi Gagal')
                            ->body('Jumlah keluar (' . $amount . ') melebihi stok yang tersedia (' . $product->quantity . ') untuk ' . $product->name . '. Stok tidak diubah.')
                            ->danger()
                            ->duration(5000)
                            ->send();
                        return;
                    }

                    $product->quantity -= $amount;
                    $product->save();
                    Notification::make()
                        ->title('Stok berhasil dikurangi.')
                        ->body('Stok produk ' . $product->name . ' telah berkurang sebanyak ' . $amount . '.')
                        ->success()
                        ->send();
                }),

            CreateAction::make()
                ->label('Tambah Produk'),
        ];
    }
}
