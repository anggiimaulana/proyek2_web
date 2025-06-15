<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NIKResource\Pages;
use App\Filament\Resources\NIKResource\RelationManagers;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\KartuKeluarga;
use App\Models\NIK;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\StatusPerkawinan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NIKResource extends Resource
{
    protected static ?string $model = NIK::class;

    protected static ?string $label = 'Data Penduduk';

    protected static ?string $pluralLabel = 'Data Penduduk';

    protected static ?string $navigationLabel = 'Data Penduduk';

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Kelola Data Penduduk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kk_id')
                    ->label('Nomor Kartu Keluarga')
                    ->options(KartuKeluarga::all()->pluck('nomor_kk', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('nomor_nik')
                    ->label('Nomor Induk Kependudukan')
                    ->placeholder('Masukan nomor induk kependudukan')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->placeholder('Masukan nama lengkap'),
                Select::make('hubungan')
                    ->label('Status Dalam Keluarga')
                    ->options(Hubungan::all()->pluck('jenis_hubungan', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('jk')
                    ->label('Jenis Kelamin')
                    ->options(JenisKelamin::all()->pluck('jenis_kelamin', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->required()
                    ->placeholder('Masukan tempat lahir'),
                DatePicker::make('tanggal_lahir')
                    ->label('Tangal Lahir')
                    ->required(),
                Select::make('agama')
                    ->label('Agama')
                    ->options(Agama::all()->pluck('nama_agama', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('pendidikan')
                    ->label('Pendidikan Terakhir')
                    ->options(Pendidikan::all()->pluck('jenis_pendidikan', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->options(Pekerjaan::all()->pluck('nama_pekerjaan', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('status')
                    ->label('Status Perkawinan')
                    ->options(StatusPerkawinan::all()->pluck('status_perkawinan', 'id'))
                    ->searchable()
                    ->required(),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->placeholder('Masukan alamat lengkap'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('name')->label('Nama Lengkap')->searchable()->sortable(),
                TextColumn::make('nomor_nik')->label('Nomor Induk Kependudukan')->searchable()->sortable(),
                TextColumn::make('kartuKeluarga.nomor_kk')->label('Nomor Kartu Keluarga')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Tanggal Pendaftaran')->dateTime()->sortable(),
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
            'index' => Pages\ListNIKS::route('/'),
            'create' => Pages\CreateNIK::route('/create'),
            'edit' => Pages\EditNIK::route('/{record}/edit'),
        ];
    }

    // Method untuk mendapatkan statistik penduduk
    public static function getWidgets(): array
    {
        return [
            NIKResource\Widgets\PendudukStatsWidget::class,
        ];
    }
}
