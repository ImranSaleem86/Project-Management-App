<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Enums\CategoryType;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('start_date'),
                DateTimePicker::make('end_date'),
                FileUpload::make('image_path')
                    ->disk('public')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->image(),
                ToggleButtons::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'on_hold' => 'On hold',
                        'in_progress' => 'In progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->grouped()
                    ->default('pending')
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
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name',function($query){
                        $query->where('type',CategoryType::Project);
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
