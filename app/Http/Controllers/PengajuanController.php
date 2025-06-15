<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class PengajuanController extends Controller
{
    public function showDetail($hashedId, $hashedName, $hashedDate)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        if (!$id) {
            abort(404);
        }

        $url = "/file/scan/{$hashedId}.{$hashedDate}.{$hashedName}";
        $urlFix = config('app.url') . $url;

        $pengajuan = Pengajuan::with(['admin', 'kuwuUpdated', 'userPengajuan'])->findOrFail($id);
        $detail = $pengajuan->detail;

        return view('file.scan.index', compact('pengajuan', 'detail', 'url'));
    }
}
