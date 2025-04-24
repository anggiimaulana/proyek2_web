<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Agama;
use App\Models\Client;
use App\Models\JenisKelamin;
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

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $label = 'Data Akun Masyarakat';
    protected static ?string $pluralLabel = 'Data Akun Masyarakat';

    protected static ?string $navigationLabel = 'Data Akun Masyarakat';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->placeholder('Masukan nama lengkap'),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->unique()
                    ->helperText('Tuliskan NIK secara lengkap dan teliti')
                    ->validationMessages([
                        'unique' => 'NIK ini sudah terdaftar.',
                    ])
                    ->placeholder('Masukan NIK'),
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
                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukan nomor telepon')
                    ->unique(ignoreRecord: true)
                    ->rule('regex:/^08[0-9]{8,11}$/')
                    ->helperText('Gunakan nomor yang aktif dan belum pernah digunakan sebelumnya.')
                    ->validationMessages([
                        'unique' => 'Nomor ini sudah terdaftar, silakan gunakan nomor lain.',
                        'regex' => 'Format nomor tidak valid. Gunakan format 08xxx.',
                    ]),
                TextInput::make('password')
                    ->label('Password')
                    ->required()
                    ->password()
                    ->placeholder('Masukan password'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Lengkap'),
                TextColumn::make('nik')->label('NIK'),
                TextColumn::make('alamat')->label('Alamat'),
                TextColumn::make('nomor_telepon')->label('Nomor Telepon'),
                TextColumn::make('created_at')->label('Tanggal Pendaftaran')->dateTime(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
