<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaResource\Pages;
use App\Filament\Resources\BeritaResource\RelationManagers;
use App\Models\Berita;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Colors\Color;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Berita Desa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('judul')
                    ->label('Judul Berita')
                    ->placeholder('Masukan judul berita')
                    ->required(),
                Select::make('kategori')
                    ->label('Kategori Berita')
                    ->options([
                        'Umum' => 'Umum',
                        'Pendidikan' => 'Pendidikan',
                        'Kesehatan' => 'Kesehatan',
                        'Ekonomi' => 'Ekonomi',
                        'Sosial' => 'Sosial',
                        'Lingkungan' => 'Lingkungan',
                        'Teknologi' => 'Teknologi',
                        'Infrastruktur' => 'Infrastruktur',
                        'Kesejahteraan' => 'Kesejahteraan',
                    ])
                    ->placeholder('Masukan kategori berita')
                    ->searchable()
                    ->required(),
                Textarea::make('isi')
                    ->label('Isi Berita')
                    ->placeholder('Masukan isi berita')
                    ->columnSpanFull()
                    ->rows(5)
                    ->required(),
                FileUpload::make('gambar')
                    ->label('Gambar Berita')
                    ->image()
                    ->disk('public')
                    ->directory('berita')
                    ->visibility('public')
                    ->previewable(true)
                    ->downloadable()
                    ->openable()
                    ->preserveFilenames()
                    ->required(),
                Select::make('penulis')
                    ->label('Penulis Berita')
                    ->options(User::whereNotNull('name')->pluck('name', 'name'))
                    ->searchable()
                    ->placeholder("Masukan penulis berita")
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')->label('No')->rowIndex(),
                TextColumn::make('judul')->label('Judul Berita')->searchable(),
                TextColumn::make('kategori')->label('Kategori Berita'),
                TextColumn::make('kategori')->badge()
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'Umum' => 'info',
                        'Pendidikan' => 'info',
                        'Kesehatan' => 'success',
                        'Ekonomi' => 'primary',
                        'Sosial' => 'primary',
                        'Lingkungan' => 'success',
                        'Teknologi' => 'info',
                        'Infrastruktur' => 'info',
                        'Kesejahteraan' => 'primary',
                    }),
                TextColumn::make('created_at')->label('Tanggal Dibuat')->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Edit')->color('warning'),
                    Tables\Actions\DeleteAction::make()->label('Hapus')->color('danger'),
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
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}
