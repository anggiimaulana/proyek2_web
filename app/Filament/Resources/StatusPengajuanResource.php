<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatusPengajuanResource\Pages;
use App\Filament\Resources\StatusPengajuanResource\RelationManagers;
use App\Models\StatusPengajuan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusPengajuanResource extends Resource
{
    protected static ?string $model = StatusPengajuan::class;

    protected static ?string $label = 'Status Pengajuan';

    protected static ?string $pluralLabel = 'Status Pengajuan';

    protected static ?string $navigationLabel = 'Status Pengajuan';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('status')
                    ->label('Status Pengajuan')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('status')->label('Status Pengajuan'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            // ->actions([
            //     Tables\Actions\EditAction::make()->label('Ubah')->color('warning'),
            //     Tables\Actions\DeleteAction::make()->label('Hapus')->color('danger'),
            // ])
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
            'index' => Pages\ListStatusPengajuans::route('/'),
            // 'create' => Pages\CreateStatusPengajuan::route('/create'),
            // 'edit' => Pages\EditStatusPengajuan::route('/{record}/edit'),
        ];
    }
}
