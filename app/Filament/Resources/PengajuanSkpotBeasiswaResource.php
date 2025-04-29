<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSkpotBeasiswaResource\Pages;
use App\Filament\Resources\PengajuanSkpotBeasiswaResource\RelationManagers;
use App\Models\Agama;
use App\Models\Hubungan;
use App\Models\JenisKelamin;
use App\Models\Pekerjaan;
use App\Models\PengajuanSkpotBeasiswa;
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

class PengajuanSkpotBeasiswaResource extends Resource
{
    protected static ?string $model = PengajuanSkpotBeasiswa::class;

    protected static ?string $label = 'Pengajuan Surat Keterangan Penghasilan Orang Tua';
    protected static ?string $pluralLabel = 'Pengajuan Surat Keterangan Penghasilan Orang Tua';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Surat Keterangan Penghasilan Orang Tua';

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
                TextInput::make('nama_ortu')
                    ->label('Nama Orang Tua')
                    ->required()
                    ->placeholder('Masukan nama orang tua'),

                Select::make('penghasilan')
                    ->label('Penghasilan Orang Tua')
                    ->options(Penghasilan::all()->pluck('rentang_penghasilan', 'id'))
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
                TextColumn::make('nama')->label('Nama Anak'),
                TextColumn::make('nama_ortu')->label('Nama Ayah'),
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Ubah'),
                    Tables\Actions\DeleteAction::make()->label('Hapus'),
                ])->label('Aksi'),
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
            'index' => Pages\ListPengajuanSkpotBeasiswas::route('/'),
            'create' => Pages\CreatePengajuanSkpotBeasiswa::route('/create'),
            'edit' => Pages\EditPengajuanSkpotBeasiswa::route('/{record}/edit'),
        ];
    }
}
