<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                // TextColumn::make('description')
                //     // ->limit(50)
                //     ->words(5)
                //     ->searchable(),
                // ImageColumn::make('image_path')
                //     ->disk('public'),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('priority')
                    ->badge(),
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('project.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updater.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'on_hold' => 'On Hold',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),
                \Filament\Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ])
                    ->label('Priority'),
                \Filament\Tables\Filters\Filter::make('due_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('due_from')
                            ->label('Due From'),
                        \Filament\Forms\Components\DatePicker::make('due_until')
                            ->label('Due Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['due_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '>=', $date),
                            )
                            ->when(
                                $data['due_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('due_date', '<=', $date),
                            );
                    }),
                \Filament\Tables\Filters\SelectFilter::make('assigned_user_id')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Assigned To'),
                \Filament\Tables\Filters\SelectFilter::make('project_id')
                    ->relationship('project', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Project'),
                \Filament\Tables\Filters\Filter::make('name')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Task Name')
                            ->placeholder('Search by task name'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['name'],
                            fn ($query, $name) => $query->where('name', 'like', "%{$name}%")
                        );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
