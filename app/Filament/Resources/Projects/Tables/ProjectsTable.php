<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                ->badge(),
                TextColumn::make('client.name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('name')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->label('Project Name')
                            ->placeholder('Search by project name'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['name'] ?? null,
                            fn ($query, $name) => $query->where('name', 'like', "%{$name}%")
                        );
                    }),
                \Filament\Tables\Filters\Filter::make('date_range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('start_from')
                            ->label('Start Date From'),
                        \Filament\Forms\Components\DatePicker::make('start_until')
                            ->label('Start Date Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    })
                    ->label('Start Date Range'),
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'on_hold' => 'On Hold',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),
                \Filament\Tables\Filters\SelectFilter::make('client_id')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Client'),
                \Filament\Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Category'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
