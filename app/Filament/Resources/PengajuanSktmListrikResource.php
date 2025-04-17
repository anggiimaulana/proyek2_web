<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanSktmListrikResource\Pages;
use App\Models\Hubungan;
use App\Models\Pekerjaan;
use App\Models\PengajuanSktmListrik;
use App\Models\Penghasilan;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class PengajuanSktmListrikResource extends Resource
{
    protected static ?string $model = PengajuanSktmListrik::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'SKTM - Listrik';

    protected static ?string $navigationGroup = 'Kelola Pengajuan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama')
                ->label('Nama Lengkap')
                ->required()
                ->placeholder('Masukan nama lengkap'),
            TextInput::make('nik')
                ->label('NIK')
                ->required()
                ->placeholder('Masukan NIK secara lengkap'),
            Select::make('hubungan')
                ->label('Hubungan Pemohon dengan Pemilik Akun')
                ->options(Hubungan::all()->pluck('jenis_hubungan', 'id'))
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
                TextColumn::make('nik')->label('NIK'),
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
            'index' => Pages\ListPengajuanSktmListriks::route('/'),
            'create' => Pages\CreatePengajuanSktmListrik::route('/create'),
            'edit' => Pages\EditPengajuanSktmListrik::route('/{record}/edit'),
        ];
    }
}
