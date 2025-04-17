<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSkUsahaResource\Pages;
use App\Filament\Resources\PengajuanSkUsahaResource\RelationManagers;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\Pekerjaan;
use App\Models\PengajuanSkUsaha;
use App\Models\StatusPerkawinan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
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

class PengajuanSkUsahaResource extends Resource
{
    protected static ?string $model = PengajuanSkUsaha::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Surat Keterangan Usaha';

    protected static ?string $navigationGroup = 'Kelola Pengajuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('hubungan')
                    ->label('Hubungan Pemohon dengan Pemilik Akun')
                    ->options(Hubungan::all()->pluck('jenis_hubungan', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->placeholder('Masukan NIK'),
                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->placeholder('Masukan nama lengkap'),
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
                Select::make('pekerjaan')
                    ->label('Pekerjaan')
                    ->options(Pekerjaan::all()->pluck('nama_pekerjaan', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('status_perkawinan')
                    ->label('Status Perkawinan')
                    ->options(StatusPerkawinan::all()->pluck('status_perkawinan', 'id'))
                    ->searchable()
                    ->required(),
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->placeholder('Masukan alamat lengkap'),
                TextInput::make('nama_usaha')
                    ->label('Nama Usaha')
                    ->required()
                    ->placeholder('Masukan nama usaha'),
                FileUpload::make('file_ktp')
                    ->label('Upload File KTP')
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
                TextColumn::make('nama')->label('Nama'),
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
            'index' => Pages\ListPengajuanSkUsahas::route('/'),
            'create' => Pages\CreatePengajuanSkUsaha::route('/create'),
            'edit' => Pages\EditPengajuanSkUsaha::route('/{record}/edit'),
        ];
    }
}
