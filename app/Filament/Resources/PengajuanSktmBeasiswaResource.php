<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSktmBeasiswaResource\Pages;
use App\Filament\Resources\PengajuanSktmBeasiswaResource\RelationManagers;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\KartuKeluarga;
use App\Models\Nik;
use App\Models\Pekerjaan;
use App\Models\PengajuanSktmBeasiswa;
use App\Models\Penghasilan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanSktmBeasiswaResource extends Resource
{
    protected static ?string $model = PengajuanSktmBeasiswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $label = 'Pengajuan Surat Keterangan Tidak Mampu - Beasiswa';
    protected static ?string $pluralLabel = 'Pengajuan Surat Keterangan Tidak Mampu - Beasiswa';

    protected static ?string $navigationLabel = 'SKTM - Beasiswa';

    protected static ?string $navigationGroup = 'Kelola Pengajuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kk_id')
                    ->label('Nomor Kartu Keluarga')
                    ->options(KartuKeluarga::all()->pluck('nomor_kk', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required(),

                Select::make('nik_id')
                    ->label('Nomor Induk Kependudukan (NIK)')
                    ->options(function (Get $get) {
                        $kkId = $get('kk_id');
                        if (!$kkId) {
                            return [];
                        }
                        return Nik::where('kk_id', $kkId)->pluck('nomor_nik', 'id');
                    })
                    ->searchable()
                    ->required()
                    ->placeholder('Pilih salah satu')
                    ->disabled(fn(Get $get) => !$get('kk_id'))
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Ambil data lengkap dari NIK yang dipilih
                        $nikData = Nik::find($state);
                        if ($nikData) {
                            // Set value field lain
                            $set('hubungan', $nikData->hubungan);
                            $set('nama_anak', $nikData->name);
                            $set('jk', $nikData->jk);
                            $set('tempat_lahir', $nikData->tempat_lahir);
                            $set('tanggal_lahir', $nikData->tanggal_lahir);
                            $set('agama', $nikData->agama);
                            $set('pekerjaan_anak', $nikData->pekerjaan);
                            $set('alamat', $nikData->alamat);
                        }
                    }),

                Select::make('hubungan')
                    ->label('Status dalam Keluarga')
                    ->options(Hubungan::all()->pluck('jenis_hubungan', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('nama_anak')
                    ->label('Nama Lengkap Anak')
                    ->required()
                    ->placeholder('Masukan nama lengkap anak'),

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

                TextInput::make('nama')
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

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->placeholder('Masukan alamat anak'),

                FileUpload::make('file_kk')
                    ->label('Upload File KK')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'])
                    ->previewable(true)
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->directory('uploads/kk')
                    ->disk('public'),

                Select::make('pengajuan.status_pengajuan')
                    ->label('Kelengkapan Berkas')
                    ->options([
                        2 => 'Lengkap',
                        3 => 'Tidak Lengkap',
                    ])
                    ->searchable()
                    ->required(fn(string $context) => $context === 'edit')
                    ->visible(fn(string $context) => $context === 'edit')
                    ->afterStateUpdated(function ($state, Set $set, Get $get, $record) {
                        if ($record?->pengajuan) {
                            $record->pengajuan->status_pengajuan = $state;
                            $record->pengajuan->save();
                        }
                    }),

                Textarea::make('pengajuan.catatan')
                    ->label('Catatan Penolakan')
                    ->placeholder('Tulis alasan penolakan di sini...')
                    ->rows(4)
                    ->required(fn(Get $get) => (int) $get('pengajuan.status_pengajuan') === 3)
                    ->columnSpan('full')
                    ->visible(fn(Get $get) => (int) $get('pengajuan.status_pengajuan') === 3),
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
                TextColumn::make('nama')->label('Nama Ayah'),
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

            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Tinjau')->color('warning'),
                    Tables\Actions\DeleteAction::make()->label('Hapus')->color('danger'),
                ])->label('Aksi'),
                Tables\Actions\Action::make('download')
                    ->label('Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->button()
                    ->color(fn($record) => $record->pengajuan?->statusPengajuan?->status === 'Disetujui' ? 'info' : 'gray')
                    ->disabled(fn($record) => $record->pengajuan?->statusPengajuan?->status !== 'Disetujui')
                    ->url(fn($record) => route('exportPdfSktmBeasiswa', $record), shouldOpenInNewTab: true),
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
