<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgamaResource\Pages;
use App\Filament\Resources\AgamaResource\RelationManagers;
use App\Models\Agama;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgamaResource extends Resource
{
    protected static ?string $model = Agama::class;

    protected static ?string $label = 'Agama';

    protected static ?string $pluralLabel = 'Agama';

    protected static ?string $navigationLabel = 'Agama';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_agama')
                    ->label('Nama Agama')
                    ->required()
                    ->placeholder('Masukan nama agama'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_agama')->label('Nama Agama'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListAgamas::route('/'),
            'create' => Pages\CreateAgama::route('/create'),
            'edit' => Pages\EditAgama::route('/{record}/edit'),
        ];
    }
}
