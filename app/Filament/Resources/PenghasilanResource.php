<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenghasilanResource\Pages;
use App\Filament\Resources\PenghasilanResource\RelationManagers;
use App\Models\Penghasilan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenghasilanResource extends Resource
{
    protected static ?string $model = Penghasilan::class;

    protected static ?string $label = 'Penghasilan';

    protected static ?string $pluralLabel = 'Penghasilan';

    protected static ?string $navigationLabel = 'Penghasilan';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('rentang_penghasilan')
                    ->label('Rentang Penghasilan')
                    ->required()
                    ->placeholder('Masukan rentang penghasilan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rentang_penghasilan')->label('Rentang Penghasilan'),
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
            'index' => Pages\ListPenghasilans::route('/'),
            'create' => Pages\CreatePenghasilan::route('/create'),
            'edit' => Pages\EditPenghasilan::route('/{record}/edit'),
        ];
    }
}
