<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSktmSekolah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSktmSekolahController extends Controller
{
    public function exportPdf(string $id)
    {
        $sktmSekolah = PengajuanSktmSekolah::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($sktmSekolah->pengajuan->id);
        $hashedName = base64_encode($sktmSekolah->nama);
        $formattedDate = $sktmSekolah->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($sktmSekolah->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.sktmSekolah', compact('sktmSekolah', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("{$sktmSekolah->nama}.pdf");
    }
}
