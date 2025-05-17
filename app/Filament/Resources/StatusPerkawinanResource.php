<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusPerkawinanResource\Pages;
use App\Filament\Resources\StatusPerkawinanResource\RelationManagers;
use App\Models\StatusPerkawinan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusPerkawinanResource extends Resource
{
    protected static ?string $model = StatusPerkawinan::class;

    protected static ?string $label = 'Status Perkawinan';

    protected static ?string $pluralLabel = 'Status Perkawinan';

    protected static ?string $navigationLabel = 'Status Perkawinan';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('status_perkawinan')
                    ->label('Status Perkawinan')
                    ->required()
                    ->placeholder('Masukan status perkawinan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('status_perkawinan')->label('Status Perkawinan'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah')->color('warning'),
                Tables\Actions\DeleteAction::make()->label('Hapus')->color('danger'),
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
            'index' => Pages\ListStatusPerkawinans::route('/'),
            'create' => Pages\CreateStatusPerkawinan::route('/create'),
            'edit' => Pages\EditStatusPerkawinan::route('/{record}/edit'),
        ];
    }
}
