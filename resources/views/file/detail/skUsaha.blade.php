<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            font-family: "Times New Roman", Times, serif;
        }

        body {
            font-size: 12pt;
            line-height: 1.5;
            padding: 40px;
        }

        .kop-surat {
            width: 100%;
            position: relative;
        }

        .logo-container {
            position: absolute;
            left: 20;
            top: 0;
            bottom: 5;
        }

        .logo {
            width: 130px;
            height: 100px;
            object-fit: contain;
        }

        .text-container {
            text-align: center;
            width: 100%;
            padding: 0 40px;
        }

        .instansi-name {
            font-size: 18px;
            margin: 0;
            line-height: 1.3;
        }

        .desa-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            line-height: 1.3;
        }

        .alamat {
            font-size: 14px;
            margin: 0;
            line-height: 1.3;
        }

        .double-line {
            margin-top: 10px;
            padding: 0;
            border: none;
        }

        .line-thick {
            height: 2px;
            background-color: black;
            margin: 0 0 3px 0;
        }

        .line-thin {
            height: 1px;
            background-color: black;
            margin: 0;
        }

        .judul {
            text-align: center;
        }

        .tabel-data {
            width: 100%;
        }

        .tabel-data td {
            text-align: justify;
            vertical-align: top;
            padding-left: 50px;
            padding-right: 50px;
        }

        .paragraf {
            text-indent: 40px;
            text-align: justify;
        }

        .ttd {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 2px;
        }

        .ttd img {
            margin-top: 5;
        }

        .ttd p {
            text-align: right;
            margin: 0;
        }
    </style>

    <title>{{ $skUsaha->nama }} - {{ $skUsaha->pengajuan->kategoriPengajuan->nama_kategori }}</title>
</head>

<body>
    @php
        $path = public_path('images/indramayu.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    @endphp

    <div class="kop-surat">
        <div class="logo-container">
            <img src="{{ $base64 }}" alt="Logo" class="logo">
        </div>
        <div class="text-container">
            <p class="instansi-name">PEMERINTAH KABUPATEN INDRAMAYU</p>
            <p class="instansi-name">KECAMATAN JATIBARANG</p>
            <p class="desa-name">DESA BULAK LOR</p>
            <p class="alamat">Jl. Raya Ampera Blok Kelir Rt 20 / 05 - Bulak Lor – Jatibarang – Indramayu</p>
        </div>
        <div class="double-line">
            <div class="line-thick"></div>
            <div class="line-thin"></div>
        </div>
    </div>

    @php
        $bulanAngka = \Carbon\Carbon::parse($skUsaha->pengajuan->created_at)->month;
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ][$bulanAngka];
    @endphp

    <div class="judul">
        <p><b><u style="font-size: 14pt;">SURAT KETERANGAN USAHA</u></b>
            <br>Nomor: {{ $skUsaha->pengajuan->id }}/Ds.2013/{{ $bulanRomawi }}/2025
        </p>
    </div>

    <p class="paragraf">
        Yang bertandatangan di bawah ini, Kuwu Desa Bulak Lor Kecamatan Jatibarang Kabupaten Indramayu, menerangkan
        dengan sebenarnya bahwa:
    </p>

    <table class="tabel-data">
        <tr>
            <td style="width: 40%">NIK</td>
            <td style="width: 60%">: {{ $skUsaha->idNikPengaju->nomor_nik }}</td>
        </tr>
        <tr>
            <td style="width: 40%">Nama</td>
            <td style="width: 60%">: {{ $skUsaha->nama }}</td>
        </tr>
        <tr>
            <td style="width: 40%">Tempat/Tanggal Lahir</td>
            <td style="width: 60%">: {{ $skUsaha->tempat_lahir }},
                {{ \Carbon\Carbon::parse($skUsaha->tanggal_lahir)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td style="width: 40%">Jenis Kelamin</td>
            <td style="width: 60%">: {{ $skUsaha->jenisKelaminPengaju->jenis_kelamin ?? '-' }}</td>
        </tr>
        <tr>
            <td style="width: 40%">Status Perkawinan</td>
            <td style="width: 60%">: {{ $skUsaha->statusPerkawinanPengaju->status_perkawinan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="width: 40%">Pekerjaan</td>
            <td style="width: 60%">: {{ $skUsaha->pekerjaanPengaju->nama_pekerjaan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="width: 40%">Alamat</td>
            <td style="width: 60%">: {{ $skUsaha->alamat }}</td>
        </tr>
    </table>

    <p class="paragraf">
        Yang bersangkutan adalah benar penduduk Desa Bulak Lor Kecamatan Jatibarang Kabupaten Indramayu, menurut catatan
        kami berkelakuan baik serta mempunyai usaha <strong>"{{ $skUsaha->nama_usaha }}".</strong>
    </p>

    <p class="paragraf">
        Demikian keterangan ini dibuat dengan sebenarnya dan dapat dipergunakan sebagaimana mestinya.
    </p>

    <div class="ttd">
        <p style="margin-top: 40">Bulak Lor,
            {{ \Carbon\Carbon::parse($skUsaha->updated_at)->translatedFormat('d F Y') }} <br>Kuwu Desa
            Bulak Lor</p>
        @if ($qrCode)
            <div style="text-align: right;">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            </div>
        @endif
        <p>
            <u><strong>{{ $skUsaha->pengajuan->kuwuUpdated->nama }}</strong></u>
        </p>
    </div>
</body>

</html>
