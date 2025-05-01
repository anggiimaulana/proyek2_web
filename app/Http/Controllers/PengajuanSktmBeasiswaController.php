<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSktmBeasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSktmBeasiswaController extends Controller
{
    public function exportPdf(string $id)
    {
        $sktmBeasiswa = PengajuanSktmBeasiswa::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($sktmBeasiswa->pengajuan->id);
        $hashedName = base64_encode($sktmBeasiswa->nama);
        $formattedDate = $sktmBeasiswa->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($sktmBeasiswa->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(pixels: 80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.sktmBeasiswa', compact('sktmBeasiswa', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("{$sktmBeasiswa->nama}.pdf");
    }
}
