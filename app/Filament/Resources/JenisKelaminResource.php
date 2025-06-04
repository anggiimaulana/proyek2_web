<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisKelaminResource\Pages;
use App\Filament\Resources\JenisKelaminResource\RelationManagers;
use App\Models\JenisKelamin;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JenisKelaminResource extends Resource
{
    protected static ?string $model = JenisKelamin::class;

    protected static ?string $label = 'Jenis Kelamin';

    protected static ?string $pluralLabel = 'Jenis Kelamin';

    protected static ?string $navigationLabel = 'Jenis Kelamin';

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->required()
                    ->placeholder('Masukan jenis kelamin'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
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
            'index' => Pages\ListJenisKelamins::route('/'),
            'create' => Pages\CreateJenisKelamin::route('/create'),
            'edit' => Pages\EditJenisKelamin::route('/{record}/edit'),
        ];
    }
}
