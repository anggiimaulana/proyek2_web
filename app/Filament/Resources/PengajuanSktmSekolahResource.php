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
use Filament\Forms\Components\Textarea;
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

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

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
                TextInput::make('tempat_lahir_ortu')
                    ->label('Tempat Lahir Orang Tua')
                    ->required()
                    ->placeholder('Masukan tempat lahir orang tua'),
                DatePicker::make('tanggal_lahir_ortu')
                    ->label('Tangal Lahir Orang Tua')
                    ->required(),
                Select::make('pekerjaan')
                    ->label('Pekerjaan Orang Tua')
                    ->options(Pekerjaan::all()->pluck('nama_pekerjaan', 'id'))
                    ->searchable()
                    ->required(),
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
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->placeholder('Masukan alamat rumah'),
                FileUpload::make('file_kk')
                    ->label('Upload File KK')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                    ->previewable(true)
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->directory('uploads/kk')
                    ->disk('public')
                    ->nullable()
                    ->dehydrated(false)
                    ->hint('Kosongkan jika tidak ingin mengganti file'),

                Select::make('pengajuan.status_pengajuan')
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

                Textarea::make('pengajuan.catatan')
                    ->label('Catatan Penolakan')
                    ->placeholder('Tulis alasan penolakan di sini...')
                    ->required()
                    ->rows(4)
                    ->columnSpan('full')
                    ->visible(fn($get) => (int) $get('pengajuan.status_pengajuan') === 3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('nama_anak')->label('Nama Anak'),
                TextColumn::make('nama')->label('Nama Orang Tua'),
                TextColumn::make('asal_sekolah')->label('Asal Sekolah'),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime(),
                TextColumn::make('updated_at')->label('Tanggal Diperbarui')->dateTime(),
                TextColumn::make('pengajuan.statusPengajuan.status')->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'Diserahkan' => 'warning',
                        'Diproses' => 'info',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        'Direvisi' => 'primary',
                    })->label('Status'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Tinjau')->color('warning'),
                    Tables\Actions\DeleteAction::make()->label('Hapus')->color('danger'),
                ])->label('Aksi'),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->button()
                    ->color(fn($record) => $record->pengajuan->statusPengajuan->status === 'Disetujui' ? 'info' : 'gray')
                    ->disabled(fn($record) => $record->pengajuan->statusPengajuan->status !== 'Disetujui')
                    ->url(fn($record) => route('exportPdfSktmSekolah', $record), shouldOpenInNewTab: true),
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
