<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HubunganResource\Pages;
use App\Filament\Resources\HubunganResource\RelationManagers;
use App\Models\Hubungan;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HubunganResource extends Resource
{
    protected static ?string $model = Hubungan::class;

    protected static ?string $label = 'Hubungan User';

    protected static ?string $pluralLabel = 'Hubungan User';

    protected static ?string $navigationLabel = 'Hubungan User';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Kelola Informasi Umum';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('jenis_hubungan')
                    ->label('Nama Hubungan')
                    ->required()
                    ->placeholder('Masukan nama hubungan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jenis_hubungan')->label('Nama Hubungan'),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
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
            'index' => Pages\ListHubungans::route('/'),
            'create' => Pages\CreateHubungan::route('/create'),
            'edit' => Pages\EditHubungan::route('/{record}/edit'),
        ];
    }
}
