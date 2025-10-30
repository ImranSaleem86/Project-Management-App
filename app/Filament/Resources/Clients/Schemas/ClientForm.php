<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                Textarea::make('address')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['active' => 'Active', 'in_active' => 'In active'])
                    ->default('active')
                    ->required(),
                Select::make('created_by')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->disabled()
                    ->dehydrated(true)
                    ->default(Auth::user()->id)
                    ->preload()
                    ->required(),
                Select::make('updated_by')
                    ->relationship('updater', 'name')
                    ->searchable()
                    ->default(Auth::user()->id)
                    ->preload()
                    ->disabled()
                    ->dehydrated(true)
                    ->required(),
            ]);
    }
}
