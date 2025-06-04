<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KuwuResource\Pages;
use App\Filament\Resources\KuwuResource\RelationManagers;
use App\Models\Agama;
use App\Models\JenisKelamin;
use App\Models\Kuwu;
use App\Models\StatusPerkawinan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KuwuResource extends Resource
{
    protected static ?string $model = Kuwu::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $label = 'Data Akun Kuwu';
    protected static ?string $pluralLabel = 'Data Akun Kuwu';

    protected static ?string $navigationLabel = 'Data Akun Kuwu';

    protected static ?string $navigationGroup = 'Kelola Akun';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->placeholder('Masukan nama lengkap'),
                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->numeric()
                    ->unique(ignoreRecord: true)
                    ->helperText('Tuliskan NIP secara lengkap dan teliti')
                    ->validationMessages([
                        'unique' => 'NIP ini sudah terdaftar.',
                    ])
                    ->placeholder('Masukan NIP'),
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
                Select::make('status')
                    ->label('Status Perkawinan')
                    ->options(StatusPerkawinan::all()->pluck('status_perkawinan', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->required()
                    ->email()
                    ->placeholder('Masukan alamat email')
                    ->unique(ignoreRecord: true)
                    ->rule('regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/')
                    ->helperText('Gunakan email @gmail.com yang aktif dan belum pernah digunakan sebelumnya.')
                    ->validationMessages([
                        'unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
                        'regex' => 'Format email tidak valid. Gunakan email dengan domain @gmail.com',
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
                TextColumn::make('name')->label('Nama Lengkap'),
                TextColumn::make('nip')->label('NIP'),
                TextColumn::make('email')->label('Email'),
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
            'index' => Pages\ListKuwus::route('/'),
            'create' => Pages\CreateKuwu::route('/create'),
            'edit' => Pages\EditKuwu::route('/{record}/edit'),
        ];
    }
}
