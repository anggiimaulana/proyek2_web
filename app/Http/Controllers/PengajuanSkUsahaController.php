<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSkUsaha;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSkUsahaController extends Controller
{
    public function exportPdf(string $id)
    {
        $skUsaha = PengajuanSkUsaha::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($skUsaha->pengajuan->id);
        $hashedName = base64_encode($skUsaha->nama);
        $formattedDate = $skUsaha->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($skUsaha->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.skUsaha', compact('skUsaha', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("{$skUsaha->nama}.pdf");
    }
}
