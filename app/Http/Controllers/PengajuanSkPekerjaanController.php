<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSkPekerjaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSkPekerjaanController extends Controller
{
    public function exportPdf(string $id)
    {
        $skPekerjaan = PengajuanSkPekerjaan::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($skPekerjaan->pengajuan->id);
        $hashedName = base64_encode($skPekerjaan->nama);
        $formattedDate = $skPekerjaan->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($skPekerjaan->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(pixels: 80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.skPekerjaan', compact('skPekerjaan', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->download(" Surat Keterangan Pekerjaan - {$skPekerjaan->nama}.pdf");
    }
}
