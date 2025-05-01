<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSkStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSkStatusController extends Controller
{
    public function exportPdf(string $id)
    {
        $skStatus = PengajuanSkStatus::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($skStatus->pengajuan->id);
        $hashedName = base64_encode($skStatus->nama);
        $formattedDate = $skStatus->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($skStatus->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(pixels: 80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.skStatus', compact('skStatus', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("{$skStatus->nama}.pdf");
    }
}
