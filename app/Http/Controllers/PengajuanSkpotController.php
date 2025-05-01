<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSkpotBeasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSkpotController extends Controller
{
    public function exportPdf(string $id)
    {
        $skpot = PengajuanSkpotBeasiswa::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($skpot->pengajuan->id);
        $hashedName = base64_encode($skpot->nama);
        $formattedDate = $skpot->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($skpot->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.skpot', compact('skpot', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("{$skpot->nama}.pdf");
    }
}
