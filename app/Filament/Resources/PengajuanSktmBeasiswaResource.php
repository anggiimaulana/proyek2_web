<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSktmBeasiswaResource\Pages;
use App\Filament\Resources\PengajuanSktmBeasiswaResource\RelationManagers;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\Pekerjaan;
use App\Models\PengajuanSktmBeasiswa;
use App\Models\Penghasilan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanSktmBeasiswaResource extends Resource
{
    protected static ?string $model = PengajuanSktmBeasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'SKTM - Beasiswa';

    protected static ?string $navigationGroup = 'Kelola Pengajuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_anak')
                    ->label('Nama Lengkap Anak')
                    ->required()
                    ->placeholder('Masukan nama lengkap anak'),
                Select::make('hubungan')
                    ->label('Hubungan Pemohon dengan Pemilik Akun')
                    ->options(Hubungan::all()->pluck('jenis_hubungan', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir Anak')
                    ->required()
                    ->placeholder('Masukan tempat lahir anak'),
                DatePicker::make('tanggal_lahir')
                    ->label('Tangal Lahir Anak')
                    ->required(),
                TextInput::make('suku')
                    ->label('Suku')
                    ->required()
                    ->placeholder('Masukan asal suku anak'),
                Select::make('agama')
                    ->label('Agama Anak')
                    ->options(Agama::all()->pluck('nama_agama', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('jk')
                    ->label('Jenis Kelamin Anak')
                    ->options(JenisKelamin::all()->pluck('jenis_kelamin', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('pekerjaan_anak')
                    ->label('Pekerjaan Anak')
                    ->options(Pekerjaan::all()->pluck('nama_pekerjaan', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('nama_ayah')
                    ->label('Nama Ayah Kandung')
                    ->required()
                    ->placeholder('Masukan nama lengkap Ayah kandung anak'),

                TextInput::make('nama_ibu')
                    ->label('Nama Ibu Kandung')
                    ->required()
                    ->placeholder('Masukan nama lengkap Ibu kandung anak'),

                Select::make('pekerjaan_ortu')
                    ->label('Pekerjaan Orang Tua')
                    ->options(Pekerjaan::all()->pluck('nama_pekerjaan', 'id'))
                    ->searchable()
                    ->required(),

                FileUpload::make('file_kk')
                    ->label('Upload File KK')
                    ->required()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                    ->previewable(true)
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->directory('uploads/kk')
                    ->disk('public'), // ✅ Disk public supaya bisa diakses

                Select::make('pengajuan.status_pengajuan_id')
                    ->label('Status Pengajuan')
                    ->options(\App\Models\StatusPengajuan::all()->pluck('status', 'id'))
                    ->default(fn($record) => $record?->pengajuan?->status_pengajuan)
                    ->searchable()
                    ->required()
                    ->afterStateUpdated(function ($state, $set, $get, $record) {
                        if ($record?->pengajuan) {
                            $record->pengajuan->status_pengajuan = $state;
                            $record->pengajuan->save();
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_anak')->label('Nama Anak'),
                TextColumn::make('nama_ayah')->label('Nama Ayah'),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime(),
                TextColumn::make('updated_at')->label('Tanggal Diperbarui')->dateTime(),
                TextColumn::make('pengajuan.statusPengajuan.status')->label('Status'),

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
            'index' => Pages\ListPengajuanSktmBeasiswas::route('/'),
            'create' => Pages\CreatePengajuanSktmBeasiswa::route('/create'),
            'edit' => Pages\EditPengajuanSktmBeasiswa::route('/{record}/edit'),
        ];
    }
}
