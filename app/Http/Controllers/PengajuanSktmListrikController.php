<?php

namespace App\Http\Controllers;

use App\Models\PengajuanSktmListrik;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanSktmListrikController extends Controller
{
    public function exportPdf(string $id)
    {
        $sktmListrik = PengajuanSktmListrik::findOrFail($id);

        // Hash ID & nama
        $hashedId = Hashids::encode($sktmListrik->pengajuan->id);
        $hashedName = base64_encode($sktmListrik->nama);
        $formattedDate = $sktmListrik->created_at->translatedFormat('d F Y');
        $hashedDate = Hashids::encode($sktmListrik->created_at->timestamp);

        // Buat link ke halaman scan
        $link = url("/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}");

        // Generate QR Code dari link
        $qrCode = base64_encode(QrCode::format('png')->size(80)->generate($link));

        // PDF
        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('file.detail.sktmListrik', compact('sktmListrik', 'qrCode'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("{$sktmListrik->nama}.pdf");
    }
}
