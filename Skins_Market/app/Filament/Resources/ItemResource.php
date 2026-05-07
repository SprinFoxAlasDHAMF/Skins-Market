<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->required(),

                        Forms\Components\TextInput::make('precio')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        Forms\Components\Select::make('tipo')
                            ->options([
                                'arma' => 'Arma',
                                'guantes' => 'Guantes',
                                'agente' => 'Agente',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Archivos Multimedia')
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Imagen de Vista Previa')
                            ->image()
                            ->directory('items/fotos')
                            ->required(),

                Forms\Components\FileUpload::make('modelo_3d')
                    ->label('Modelo 3D (Opcional)')
                    ->directory('items/models')
                    ->preserveFilenames()
                ])->columns(2),

                Forms\Components\Section::make('Atributos')
                    ->schema([
                        Forms\Components\Select::make('calidad_id')
                            ->relationship('calidad', 'nombre')
                            ->required(),

                        Forms\Components\Select::make('categoria_id')
                            ->relationship('categoria', 'nombre')
                            ->required(),

                        Forms\Components\Select::make('exterior_id')
                            ->relationship('exterior', 'nombre'),

                        Forms\Components\Select::make('color_id')
                            ->relationship('color', 'nombre'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')->searchable(),
                Tables\Columns\TextColumn::make('precio'),
                Tables\Columns\TextColumn::make('calidad.nombre'),
                Tables\Columns\TextColumn::make('tipo'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('calidad')
                    ->relationship('calidad', 'nombre'),

                Tables\Filters\SelectFilter::make('tipo')
                    ->options([
                        'arma' => 'Arma',
                        'guantes' => 'Guantes',
                        'agente' => 'Agente',
                    ]),
            ]);
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }    
}
