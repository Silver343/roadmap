<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers;
use App\Models\Board;
use App\Models\Project;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Ramsey\Uuid\Uuid;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?string $navigationGroup = 'Manage';

    public static function form(Form $form): Form
    {
        $data = [];

        $uuid1 = Uuid::uuid4()->toString();
        $uuid2 = Uuid::uuid4()->toString();

        $data[$uuid1] = [
            'title' => 'Test 1234',
            'description' => null,
        ];

        $data[$uuid2] = [
            'title' => 'Test jklajsdlasd',
            'description' => null,
        ];

        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('title')
                        ->columnSpan(1)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('url')
                        ->columnSpan(1)
                        ->maxLength(255),
                    Forms\Components\MarkdownEditor::make('description')
                        ->columnSpan(2)
                        ->maxLength(65535),
                    Forms\Components\HasManyRepeater::make('boards')
                        ->relationship('boards')
                        ->orderable('sort_order')
                        ->columnSpan(2)
//                        ->afterStateHydrated(function ($component) use ($data) {
////                            $component->state($data);
//                        })
                        ->schema([
                            Forms\Components\Toggle::make('visible'),
                            Forms\Components\TextInput::make('title'),
                            Forms\Components\Textarea::make('description'),
                        ]),
                ])->columns()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('boards_count')->sortable()->counts('boards'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Date'),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
