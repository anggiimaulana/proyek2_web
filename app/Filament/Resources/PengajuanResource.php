<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanResource\Pages;
use App\Filament\Resources\PengajuanResource\Widgets;
use App\Filament\Resources\PengajuanResource\Widgets\PengajuanPerBulanChart;
use App\Models\Pengajuan;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PengajuanResource extends Resource
{
    protected static ?string $model = Pengajuan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Pengajuan Surat';

    public static function getNavigationBadge(): ?string
    {
        return (string) Pengajuan::whereIn('status_pengajuan', [1, 2, 3, 5])->count();
    }

    // Form untuk filter
    public function getFiltersForm(): array
    {
        return [
            Select::make('range_type')
                ->label('Periode Waktu')
                ->options(PengajuanPerBulanChart::getRangeTypeOptions())
                ->default('auto_yearly')
                ->selectablePlaceholder(false)
                ->live() // Realtime update saat pilihan berubah
                ->afterStateUpdated(function () {
                    // Refresh widget setelah filter berubah
                    $this->dispatch('$refresh');
                })
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                TextColumn::make('nama')
                    ->label('Nama Pengaju')
                    ->getStateUsing(function ($record) {
                        return $record->detail?->nama ?? $record->detail?->name ?? '-';
                    }),
                TextColumn::make('kategoriPengajuan.nama_kategori')->label('Jenis Pengajuan'),
                TextColumn::make('detail.created_at')->label('Tanggal Pengajuan')->dateTime(),
                TextColumn::make('statusPengajuan.status')
                    ->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'Diserahkan' => 'warning',
                        'Diproses' => 'info',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        'Direvisi' => 'primary',
                    })->label('Status Pengajuan'),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->relationship('statusPengajuan', 'status')
                    ->label('Status Pengajuan'),
            ])
            ->actions([])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Daftarkan widget untuk resource ini
    public static function getWidgets(): array
    {
        return [
            Widgets\PengajuanStatsOverview::class,
            Widgets\PengajuanPerBulanChart::class,
            Widgets\JenisSuratChart::class,
        ];
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuans::route('/'),
        ];
    }
}
