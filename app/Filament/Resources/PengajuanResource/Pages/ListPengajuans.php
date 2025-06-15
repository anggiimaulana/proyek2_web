<?php

// Tambahkan ini ke file Page Resource yang sudah ada
// Misalnya di: App\Filament\Resources\PengajuanResource\Pages\ListPengajuans.php

namespace App\Filament\Resources\PengajuanResource\Pages;

use App\Filament\Resources\PengajuanResource;
use App\Filament\Resources\PengajuanResource\Widgets\JenisSuratChart;
use App\Filament\Resources\PengajuanResource\Widgets\PengajuanPerBulanChart;
use App\Filament\Resources\PengajuanResource\Widgets\PengajuanStatsOverview;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListPengajuans extends ListRecords
{
    protected static string $resource = PengajuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    // Tambahkan method ini untuk menampilkan widget
    protected function getHeaderWidgets(): array
    {
        return [
            PengajuanStatsOverview::class,
            PengajuanPerBulanChart::class,
            JenisSuratChart::class
        ];
    }

    // Tambahkan method ini untuk filter
    public function getHeaderWidgetsColumns(): int
    {
        return 1; // Full width
    }

    // Filter untuk widget
    public function getWidgetFilters(): array
    {
        return [
            'range_type' => Select::make('range_type')
                ->label('Periode Waktu')
                ->options([
                    'auto_yearly' => 'Mei ke Mei (Otomatis)',
                    'last_12_months' => '12 Bulan Terakhir',
                    'current_year' => 'Tahun Sekarang',
                    'last_year' => 'Tahun Lalu',
                    'academic_year' => 'Tahun Akademik (Jul-Jun)',
                    'fiscal_year' => 'Tahun Fiskal (Apr-Mar)',
                    'smart_range' => 'Smart Range',
                    'last_6_months' => '6 Bulan Terakhir',
                    'year_to_date' => 'Year to Date'
                ])
                ->default('auto_yearly')
                ->selectablePlaceholder(false)
        ];
    }

    public function getSubheading(): ?string
    {
        return 'Pantau data pengajuan surat';
    }
}

// ATAU cara paling mudah, buat komponen filter terpisah:

// File: app/Filament/Widgets/PeriodFilter.php
use Filament\Widgets\Widget;

class PeriodFilter extends Widget
{
    protected static string $view = 'filament.widgets.period-filter';
    protected static ?int $sort = -1; // Tampil di atas

    public $rangeType = 'auto_yearly';

    public function mount(): void
    {
        $this->form->fill([
            'range_type' => $this->rangeType
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('range_type')
                ->label('Pilih Periode Waktu')
                ->options([
                    'auto_yearly' => 'Mei ke Mei (Otomatis)',
                    'last_12_months' => '12 Bulan Terakhir',
                    'current_year' => 'Tahun Sekarang',
                    'last_year' => 'Tahun Lalu',
                    'academic_year' => 'Tahun Akademik (Jul-Jun)',
                    'fiscal_year' => 'Tahun Fiskal (Apr-Mar)',
                    'smart_range' => 'Smart Range',
                    'last_6_months' => '6 Bulan Terakhir',
                    'year_to_date' => 'Year to Date'
                ])
                ->default('auto_yearly')
                ->selectablePlaceholder(false)
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    $this->rangeType = $state;
                    $this->dispatch('rangeTypeChanged', $state);
                })
        ];
    }
}
