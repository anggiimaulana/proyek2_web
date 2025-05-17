<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKeluargaResource\Pages;
use App\Filament\Resources\KartuKeluargaResource\RelationManagers;
use App\Models\KartuKeluarga;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KartuKeluargaResource extends Resource
{
    protected static ?string $model = KartuKeluarga::class;

    protected static ?string $label = 'Data Kartu Keluarga Penduduk';

    protected static ?string $pluralLabel = 'Data Kartu Keluarga Penduduk';

    protected static ?string $navigationLabel = 'Data Kartu Keluarga Penduduk';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Kelola Data Penduduk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor_kk')
                    ->label('Nomor Kartu Keluarga')
                    ->placeholder('Masukan nomor kartu keluarga')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->numeric(),
                TextInput::make('kepala_keluarga')
                    ->label('Nama Kepala Keluarga')
                    ->required()
                    ->placeholder('Masukan nama kepala keluarga'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('kepala_keluarga')->label('Nama Kepala Keluarga'),
                TextColumn::make('nomor_kk')->label('Nomor Kartu Keluarga'),
                TextColumn::make('created_at')->label('Tanggal Pendaftaran')->dateTime(),
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
            'index' => Pages\ListKartuKeluargas::route('/'),
            'create' => Pages\CreateKartuKeluarga::route('/create'),
            'edit' => Pages\EditKartuKeluarga::route('/{record}/edit'),
        ];
    }
}
