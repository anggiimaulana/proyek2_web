<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSkStatusResource\Pages;
use App\Filament\Resources\PengajuanSkStatusResource\RelationManagers;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\Pekerjaan;
use App\Models\PengajuanSkStatus;
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

class PengajuanSkStatusResource extends Resource
{
    protected static ?string $model = PengajuanSkStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $label = 'Pengajuan Surat Keterangan Status';
    protected static ?string $pluralLabel = 'Pengajuan Surat Keterangan Status';

    protected static ?string $navigationLabel = 'Surat Keterangan Status';

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
                    ->label('Nama Lengkap')
                    ->required()
                    ->placeholder('Masukan nama lengkap'),
                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->required()
                    ->placeholder('Masukan tempat lahir'),
                DatePicker::make('tanggal_lahir')
                    ->label('Tangal Lahir')
                    ->required(),
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
                    ->placeholder('Masukan alamat'),
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
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('nama')->label('Nama'),
                TextColumn::make('created_at')->label('Tanggal Pengajuan')->dateTime(),
                TextColumn::make('updated_at')->label('Tanggal Diperbarui')->dateTime(),
                TextColumn::make('pengajuan.statusPengajuan.status')->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'Diserahkan' => 'warning',
                        'Diproses' => 'info',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
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
                    ->color(fn($record) => $record->pengajuan->statusPengajuan->status === 'Disetujui' ? 'info' : 'gray')
                    ->disabled(fn($record) => $record->pengajuan->statusPengajuan->status !== 'Disetujui')
                    ->url(fn($record) => route('exportPdfSkStatus', $record), shouldOpenInNewTab: true),
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
            'index' => Pages\ListPengajuanSkStatuses::route('/'),
            'create' => Pages\CreatePengajuanSkStatus::route('/create'),
            'edit' => Pages\EditPengajuanSkStatus::route('/{record}/edit'),
        ];
    }
}
