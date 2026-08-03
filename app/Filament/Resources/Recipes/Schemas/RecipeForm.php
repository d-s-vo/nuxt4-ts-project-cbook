<?php

declare(strict_types=1);

namespace App\Filament\Resources\Recipes\Schemas;

use App\Data\Repositories\UserRepository;
use App\Enums\Difficulty;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RecipeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Author')
                    ->options(fn (): array => app(UserRepository::class)->selectOptions())
                    ->searchable()
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('cooking_time')
                    ->label('Cooking time, minutes')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                TextInput::make('servings')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Select::make('difficulty')
                    ->options(Difficulty::class)
                    ->required(),
                Repeater::make('steps')
                    ->simple(
                        TextInput::make('step')
                            ->required()
                    )
                    ->required()
                    ->minItems(1)
                    ->columnSpanFull(),
                Repeater::make('ingredients')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('unit')
                            ->required()
                            ->maxLength(50),
                    ])
                    ->columns(3)
                    ->required()
                    ->minItems(1)
                    ->columnSpanFull(),
            ]);
    }
}
