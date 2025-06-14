<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSktmListrikResource\Pages;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\KartuKeluarga;
use App\Models\Nik;
use App\Models\Pekerjaan;
use App\Models\PengajuanSktmListrik;
use App\Models\Penghasilan;
use Filament\Forms;
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
use Illuminate\Support\Facades\Storage;

class PengajuanSktmListrikResource extends Resource
{
    protected static ?string $model = PengajuanSktmListrik::class;

    protected static ?string $label = 'Pengajuan Surat Keterangan Tidak Mampu - Listrik';
    protected static ?string $pluralLabel = 'Pengajuan Surat Keterangan Tidak Mampu - Listrik';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'SKTM - Listrik';

    protected static ?string $navigationGroup = 'Kelola Pengajuan';

    public static function form(Form $form): Form
    {
        return $form->schema([
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
                            $set('nama', $nikData->name);
                            $set('jk', $nikData->jk);
                            $set('tempat_lahir', $nikData->tempat_lahir);
                            $set('tanggal_lahir', $nikData->tanggal_lahir);
                            $set('agama', $nikData->agama);
                            $set('pekerjaan', $nikData->pekerjaan);
                            $set('status_perkawinan', $nikData->status);
                            $set('alamat', $nikData->alamat);
                        }
                    }),

            Select::make('hubungan')
                ->label('Status dalam Keluarga')
                ->options(Hubungan::all()->pluck('jenis_hubungan', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('nama')
                ->label('Nama Lengkap')
                ->required()
                ->placeholder('Masukan nama lengkap'),

            TextInput::make('umur')
                ->label('Umur')
                ->numeric()
                ->required()
                ->placeholder('Masukan umur'),

            Select::make('jk')
                ->label('Jenis Kelamin')
                ->options(JenisKelamin::all()->pluck('jenis_kelamin', 'id'))
                ->searchable()
                ->required(),

            Select::make('agama')
                ->label('Agama')
                ->options(Agama::all()->pluck('nama_agama', 'id'))
                ->searchable()
                ->required(),

            Textarea::make('alamat')
                ->label('Alamat')
                ->required()
                ->placeholder('Masukan alamat pengaju'),

            Select::make('pekerjaan')
                ->label('Pekerjaan')
                ->options(Pekerjaan::all()->pluck('nama_pekerjaan', 'id'))
                ->searchable()
                ->required(),

            Select::make('penghasilan')
                ->label('Rentang Penghasilan')
                ->options(Penghasilan::all()->pluck('rentang_penghasilan', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('nama_pln')
                ->label('Nama yang tercantum dalam PLN')
                ->required()
                ->placeholder('Masukan nama yang tercantum dalam PLN'),

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

                        $record->touch();
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
                TextColumn::make('nama')->label('Nama')->searchable()->sortable(),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime()->sortable(),
                TextColumn::make('updated_at')->label('Tanggal Diperbarui')->dateTime()->sortable(),
                TextColumn::make('pengajuan.statusPengajuan.status')->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'Diserahkan' => 'warning',
                        'Diproses' => 'info',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        'Direvisi' => 'primary',
                    })->label('Status')->sortable(),
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
                    ->color(fn($record) => $record->pengajuan?->statusPengajuan?->status === 'Disetujui' ? 'info' : 'gray')
                    ->disabled(fn($record) => $record->pengajuan?->statusPengajuan?->status !== 'Disetujui')
                    ->url(fn($record) => route('exportPdfSktmListrik', $record), shouldOpenInNewTab: true),
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
            'index' => Pages\ListPengajuanSktmListriks::route('/'),
            'create' => Pages\CreatePengajuanSktmListrik::route('/create'),
            'edit' => Pages\EditPengajuanSktmListrik::route('/{record}/edit'),
        ];
    }
}
