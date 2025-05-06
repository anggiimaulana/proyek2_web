<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendidikanResource\Pages;
use App\Filament\Resources\PendidikanResource\RelationManagers;
use App\Models\Pendidikan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendidikanResource extends Resource
{
    protected static ?string $model = Pendidikan::class;

    protected static ?string $label = 'Pendidikan';

    protected static ?string $pluralLabel = 'Pendidikan';

    protected static ?string $navigationLabel = 'Pendidikan';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('jenis_pendidikan')
                    ->label('Pendidikan')
                    ->required()
                    ->placeholder('Masukan pendidikan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_pendidikan')->label('Pendidikan'),
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
            'index' => Pages\ListPendidikans::route('/'),
            'create' => Pages\CreatePendidikan::route('/create'),
            'edit' => Pages\EditPendidikan::route('/{record}/edit'),
        ];
    }
}
