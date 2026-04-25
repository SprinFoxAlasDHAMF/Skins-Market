<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArmaResource\Pages;
use App\Filament\Resources\ArmaResource\RelationManagers;
use App\Models\Arma;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
class ArmaResource extends Resource
{
    protected static ?string $model = Arma::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('item_id')
                    ->label('Item')
                    ->relationship('item', 'nombre')
                    ->searchable()
                    ->required(),

                Select::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre')
                    ->required(),

                Select::make('exterior_id')
                    ->label('Exterior')
                    ->relationship('exterior', 'nombre')
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item.nombre')
                    ->label('Skin')
                    ->searchable(),

                TextColumn::make('categoria.nombre')
                    ->label('Categoría'),

                TextColumn::make('exterior.nombre')
                    ->label('Exterior'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categoria')
                    ->relationship('categoria', 'nombre'),

                Tables\Filters\SelectFilter::make('exterior')
                    ->relationship('exterior', 'nombre'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListArmas::route('/'),
            'create' => Pages\CreateArma::route('/create'),
            'edit' => Pages\EditArma::route('/{record}/edit'),
        ];
    }    
}
