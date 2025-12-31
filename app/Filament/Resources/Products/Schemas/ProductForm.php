<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->required(),
                Select::make('type')
                    ->options([
                        'food'  => 'Makanan',
                        'drink' => 'Minuman',
                    ])
                    ->label('Nama Produk')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('quantity')
                    ->label('Kuantitas')
                    ->numeric()
                    ->default(0)
                    ->readOnly(),
            ]);
    }
}
