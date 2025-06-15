<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriPengajuanResource\Pages;
use App\Filament\Resources\KategoriPengajuanResource\RelationManagers;
use App\Models\KategoriPengajuan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategoriPengajuanResource extends Resource
{
    protected static ?string $model = KategoriPengajuan::class;

    protected static ?string $label = 'Kategori Pengajuan';

    protected static ?string $pluralLabel = 'Kategori Pengajuan';

    protected static ?string $navigationLabel = 'Kategori Pengajuan';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_kategori')
                    ->label('Kategori Pengajuan')
                    ->required()
                    ->placeholder('Masukan kategori pengajuan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('nama_kategori')->label('Kategori Pengajuan'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
            ])
            ->filters([
                //
            ])
            // ->actions([
            //     Tables\Actions\EditAction::make()->label('Ubah')->color('warning'),
            //     Tables\Actions\DeleteAction::make()->label('Hapus')->color('danger'),
            // ])
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
            'index' => Pages\ListKategoriPengajuans::route('/'),
            // 'create' => Pages\CreateKategoriPengajuan::route('/create'),
            // 'edit' => Pages\EditKategoriPengajuan::route('/{record}/edit'),
        ];
    }
}
