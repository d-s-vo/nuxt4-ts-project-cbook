<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recipes\Tables;

use App\Models\Recipe;
use App\Tasks\DeleteRecipeTask;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class RecipesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Author')
                    ->searchable(),
                TextColumn::make('difficulty')
                    ->badge()
                    ->sortable(),
                TextColumn::make('cooking_time')
                    ->label('Cooking time')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('servings')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ingredients_count')
                    ->label('Ingredients')
                    ->counts('ingredients'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->using(function (Collection $records): void {
                            $deleteRecipe = app(DeleteRecipeTask::class);

                            foreach ($records as $record) {
                                assert($record instanceof Recipe);
                                $deleteRecipe->run($record->id);
                            }
                        }),
                ]),
            ]);
    }
}
