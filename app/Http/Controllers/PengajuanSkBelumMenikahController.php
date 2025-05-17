<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSkBelumMenikah;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSkBelumMenikahController extends Controller
{
    public function exportPdf(string $id)
    {
        $skBelumMenikah = PengajuanSkBelumMenikah::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($skBelumMenikah->pengajuan->id);
        $hashedName = base64_encode($skBelumMenikah->nama);
        $formattedDate = $skBelumMenikah->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($skBelumMenikah->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.skBelumMenikah', compact('skBelumMenikah', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->download(" SK Belum Menikah - {$skBelumMenikah->nama}.pdf");
    }
}
