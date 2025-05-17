<?php

namespace App\Filament\KuwuPanel\Resources;

use App\Filament\KuwuPanel\Resources\PengajuanResource\Pages;
use App\Filament\KuwuPanel\Resources\PengajuanResource\RelationManagers;
use App\Models\Pengajuan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Collection;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PengajuanResource extends Resource
{
    protected static ?string $model = Pengajuan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return (string) Pengajuan::whereIn('status_pengajuan', [2])->count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('nama')
                    ->label('Nama Pengaju')
                    ->getStateUsing(function ($record) {
                        return $record->detail?->nama ?? $record->detail?->name ?? '-';
                    }),
                TextColumn::make('kategoriPengajuan.nama_kategori')->label('Jenis Pengajuan'),
                TextColumn::make('detail.created_at')->label('Tanggal Pengajuan')->dateTime(),
                TextColumn::make('detail.updated_at')->label('Tanggal Diperbarui')->dateTime(),
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
            ->actions([
                Tables\Actions\Action::make('Aksi')
                    ->label(function ($record) {
                        if ($record->status_pengajuan == 4) {
                            return 'Telah Disetujui';
                        } elseif ($record->statusPengajuan->status === 'Diproses' || $record->status_pengajuan == 2) {
                            return 'Setujui';
                        } else {
                            return 'Menunggu Diproses';
                        }
                    })
                    ->button()
                    ->color(function ($record) {
                        if ($record->status_pengajuan == 4) {
                            return 'success';
                        } elseif ($record->statusPengajuan->status === 'Diproses' || $record->status_pengajuan == 2) {
                            return 'info';
                        } else {
                            return 'gray';
                        }
                    })
                    ->disabled(function ($record) {
                        return !($record->statusPengajuan->status === 'Diproses' || $record->status_pengajuan == 2);
                    })
                    ->action(function ($record) {
                        $record->update(['status_pengajuan' => 4]);
                    })
                    ->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('Setujui yang Diproses')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (EloquentCollection $records) {
                            $disetujui = 0;
                            $gagal = 0;

                            $records->each(function ($record) use (&$disetujui, &$gagal) {
                                if ($record->status_pengajuan == 2 || $record->statusPengajuan?->status === 'Diproses') {
                                    $record->update(['status_pengajuan' => 4]);
                                    $disetujui++;
                                } else {
                                    $gagal++;
                                }
                            });

                            // Notifikasi berhasil
                            if ($disetujui > 0) {
                                Notification::make()
                                    ->title("Berhasil menyetujui $disetujui pengajuan")
                                    ->success()
                                    ->send();
                            }

                            // Notifikasi gagal (hanya tampil jika ada yang gagal)
                            if ($gagal > 0) {
                                Notification::make()
                                    ->title("$gagal pengajuan tidak bisa disetujui")
                                    ->body('Pastikan statusnya sudah Diproses.')
                                    ->danger()
                                    ->send();
                            }
                        }),
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
            'index' => Pages\ListPengajuans::route('/'),
            'create' => Pages\CreatePengajuan::route('/create'),
            'edit' => Pages\EditPengajuan::route('/{record}/edit'),
        ];
    }
}
