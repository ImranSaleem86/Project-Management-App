<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ToggleButtons;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description')
                    ->default(null),
                FileUpload::make('image_path')
                    ->disk('public')
                    ->preserveFilenames()
                    ->downloadable()
                    ->openable()
                    ->directory('tasks')
                    ->image(),
                ToggleButtons::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'on_hold' => 'On hold',
                        'in_progress' => 'In progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->colors([
                        'pending' => 'warning',
                        'on_hold' => 'info',
                        'in_progress' => 'secondary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    ])
                    ->grouped()
                    ->default('pending')
                    ->required(),
                ToggleButtons::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                    ->default('medium')
                    ->colors([
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                    ])
                    ->icons([
                        'low' => Heroicon::OutlinedPencil,
                        'medium' => Heroicon::OutlinedClock,
                        'high' => Heroicon::OutlinedCheckCircle,
                    ])
                    ->grouped()
                    ->required(),
                DatePicker::make('due_date'),
                Select::make('assigned_user_id')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload()
                    ->default(null),
                Select::make('project_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->relationship('project', 'name'),
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
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
            ]);
    }
}
