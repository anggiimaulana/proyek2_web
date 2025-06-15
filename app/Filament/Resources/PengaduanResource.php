<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
use App\Models\Client;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pengaduan Masyarakat';

    public static function form(Form $form): Form
    {
        $isEditPage = request()->routeIs('filament.admin.resources.pengaduans.edit');

        return $form
            ->schema([
                Select::make('client_id')
                    ->label('Nama Pelapor')
                    ->options(Client::whereNotNull('nama_kepala_keluarga')->pluck('nama_kepala_keluarga', 'id'))
                    ->required(),

                $isEditPage
                    ? TextInput::make('jenis_layanan')
                    ->label('Jenis Layanan')
                    ->required()
                    : Select::make('jenis_layanan')
                    ->label('Jenis Layanan')
                    ->options([
                        'Sosial'        => 'Sosial',
                        'Lingkungan'    => 'Lingkungan',
                        'Infrastruktur' => 'Infrastruktur',
                    ])
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn(string $state, callable $set) => $set('kategori', null)),

                Textarea::make('keluhan')
                    ->label('Keluhan')
                    ->required(),

                Textarea::make('lokasi')
                    ->label('Lokasi')
                    ->required(),

                $isEditPage
                    ? TextInput::make('kategori')
                    ->label('Kategori')
                    ->required()
                    : Select::make('kategori')
                    ->label('Kategori')
                    ->options(function (callable $get) {
                        return match ($get('jenis_layanan')) {
                            'sosial' => [
                                'tawuran'                => 'Tawuran',
                                'masalah_rumah_tangga'   => 'Masalah Rumah Tangga',
                                'bansos'                 => 'Bansos',
                                'Kesehatan'              => 'Kesehatan',
                                'Pendidikan'             => 'Pendidikan',
                                'Lainnya'                => 'Lainnya'
                            ],
                            'lingkungan' => [
                                'Kebersihan'     => 'Kebersihan',
                                'sampah'         => 'Sampah',
                                'Pertanian'      => 'Pertanian',
                                'Penghijauan'    => 'Penghijauan',
                                'air_limbah'     => 'Air Limbah',
                                'Lainnya'        => 'Lainnya'
                            ],
                            'infrastruktur' => [
                                'jalan_rusak'         => 'Jalan Rusak',
                                'penerangan_Jalan'    => 'Penerangan Jalan',
                                'Bangunan_Rusak'      => 'Bangunan Rusak',
                                'Drainase'            => 'Drainase',
                                'Lainnya'             => 'Lainnya'
                            ],
                            default => [],
                        };
                    })
                    ->placeholder('Pilih jenis layanan terlebih dahulu')
                    ->required(),

                FileUpload::make('gambar')
                    ->label('Bukti Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('pengaduan')
                    ->visibility('public')
                    ->required(),

                Select::make('status')
                    ->label('Status Pengaduan')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Diproses' => 'Diproses',
                        'Selesai'  => 'Selesai',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.nama_kepala_keluarga')->label('Pelapor')->searchable(),
                TextColumn::make('jenis_layanan')->label('Jenis Layanan'),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning'    => 'Menunggu',
                        'info' => 'Diproses',
                        'success' => 'Selesai',
                    ]),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Diproses' => 'Diproses',
                        'Selesai'  => 'Selesai',
                    ]),
                SelectFilter::make('kategori')
                    ->label('Filter Kategori')
                    ->options([
                        'tawuran'              => 'Tawuran',
                        'masalah_rumah_tangga' => 'Masalah Rumah Tangga',
                        'bansos'               => 'Bansos',
                        'sampah'               => 'Sampah',
                        'air_limbah'           => 'Air Limbah',
                        'jalan_rusak'          => 'Jalan Rusak',
                        'Bangunan_rusak'       => 'Bangunan Rusak',
                        'penerangan_jalan'     => 'Penerangan jalan',
                        'Drainase'             => 'Drainase',
                        'Pertanian'            => 'Pertanian',
                        'Kesehatan'            => 'Kesehatan',
                        'Pendidikan'           => 'Pendidikan',
                        'Penghijauan'          => 'Penghijauan',
                        'Kebersihan'           => 'Kebersihan',
                        'Lainnya'              => 'Lainnya'
                    ]),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPengaduans::route('/'),
            'create' => Pages\CreatePengaduan::route('/create'),
            'edit'   => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }
}
