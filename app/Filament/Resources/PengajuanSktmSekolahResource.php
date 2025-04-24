<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSktmSekolahResource\Pages;
use App\Filament\Resources\PengajuanSktmSekolahResource\RelationManagers;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\Pekerjaan;
use App\Models\PengajuanSktmSekolah;
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

class PengajuanSktmSekolahResource extends Resource
{
    protected static ?string $model = PengajuanSktmSekolah::class;

    protected static ?string $label = 'Pengajuan Surat Keterangan Tidak Mampu - Sekolah';
    protected static ?string $pluralLabel = 'Pengajuan Surat Keterangan Tidak Mampu - Sekolah';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'SKTM - Sekolah';

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
                TextInput::make('nama')
                    ->label('Nama Lengkap Orang Tua')
                    ->required()
                    ->placeholder('Masukan nama lengkap orang tua kandung anak'),
                TextInput::make('nama_anak')
                    ->label('Nama Lengkap Anak')
                    ->required()
                    ->placeholder('Masukan nama lengkap anak'),
                Select::make('jk')
                    ->label('Jenis Kelamin Anak')
                    ->options(JenisKelamin::all()->pluck('jenis_kelamin', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir Anak')
                    ->required()
                    ->placeholder('Masukan tempat lahir anak'),
                DatePicker::make('tanggal_lahir')
                    ->label('Tangal Lahir Anak')
                    ->required(),
                Select::make('agama')
                    ->label('Agama Anak')
                    ->options(Agama::all()->pluck('nama_agama', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('asal_sekolah')
                    ->label('Asal Sekolah Anak')
                    ->required()
                    ->placeholder('Masukan asal sekolah anak'),
                TextInput::make('kelas')
                    ->label('Kelas')
                    ->required()
                    ->placeholder('Masukan kelas anak di sekolah'),
                FileUpload::make('file_kk')
                    ->label('Upload File KK')
                    ->required()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                    ->previewable(true)
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->directory('uploads/kk')
                    ->disk('public'),

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
                TextColumn::make('nama')->label('Nama Orang Tua'),
                TextColumn::make('asal_sekolah')->label('Asal Sekolah'),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime(),
                TextColumn::make('updated_at')->label('Tanggal Diperbarui')->dateTime(),
                TextColumn::make('pengajuan.statusPengajuan.status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Diserahkan' => 'warning',
                        'Diproses' => 'info',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                    })->label('Status'),
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
            'index' => Pages\ListPengajuanSktmSekolahs::route('/'),
            'create' => Pages\CreatePengajuanSktmSekolah::route('/create'),
            'edit' => Pages\EditPengajuanSktmSekolah::route('/{record}/edit'),
        ];
    }
}
