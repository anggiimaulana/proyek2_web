<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Agama;
use App\Models\Client;
use App\Models\JenisKelamin;
use App\Models\KartuKeluarga;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\StatusPerkawinan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $label = 'Data Akun Pengguna';
    protected static ?string $pluralLabel = 'Data Akun Pengguna';

    protected static ?string $navigationLabel = 'Data Akun Pengguna';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Kelola Akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('kk_id')
                    ->label('Nomor Kartu Keluarga')
                    ->options(KartuKeluarga::all()->pluck('nomor_kk', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $kkData = KartuKeluarga::find($state);
                        if ($kkData) {
                            $set('nama_kepala_keluarga', $kkData->kepala_keluarga);
                        }
                    }),

                TextInput::make('nama_kepala_keluarga')
                    ->label('Nama Kepala Keluarga')
                    ->required(),
                    
                TextInput::make('nomor_telepon')
                    ->label('Nomor Telepon')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukan nomor telepon')
                    ->unique(ignoreRecord: true)
                    ->rule('regex:/^08[0-9]{9,14}$/')
                    ->helperText('Gunakan nomor yang aktif dan belum pernah digunakan sebelumnya.')
                    ->validationMessages([
                        'unique' => 'Nomor ini sudah terdaftar, silakan gunakan nomor lain.',
                        'regex' => 'Format nomor tidak valid. Gunakan format 08xxx dan minimal 11 angka.',
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
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('nama_kepala_keluarga')->label('Nama Kepala Keluarga'),
                TextColumn::make('nomor_telepon')->label('Nomor Telepon'),
                TextColumn::make('created_at')->label('Tanggal Pendaftaran')->dateTime(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Ubah')->color('warning'),
                Tables\Actions\DeleteAction::make()->label('Hapus')->color('danger'),
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
