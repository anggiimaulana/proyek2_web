<?php

namespace App\Filament\KuwuPanel\Resources;

use App\Filament\KuwuPanel\Resources\NikResource\Pages;
use App\Filament\KuwuPanel\Resources\NikResource\RelationManagers;
use App\Models\Nik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NikResource extends Resource
{
    protected static ?string $model = Nik::class;

    protected static ?string $label = 'Data Penduduk';

    protected static ?string $pluralLabel = 'Data Penduduk';

    protected static ?string $navigationLabel = 'Data Penduduk';

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Data Penduduk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('name')->label('Nama Lengkap'),
                TextColumn::make('nomor_nik')->label('Nomor Induk Kependudukan'),
                TextColumn::make('created_at')->label('Tanggal Pendaftaran')->dateTime(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // 
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
            'index' => Pages\ListNiks::route('/'),
            'create' => Pages\CreateNik::route('/create'),
            'edit' => Pages\EditNik::route('/{record}/edit'),
        ];
    }
}
