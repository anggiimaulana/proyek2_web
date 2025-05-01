<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>Detail Pengajuan</h1>
        <table class="table">
            <tr>
                <td>ID Pengajuan</td>
                <td>{{ $pengajuan->id }}</td>
            </tr>
            <tr>
                <td>Url</td>
                <td><a href="{{ $url }}" target="_blank">127.0.0.1:8000/{{ $url }}</a>
                </td>
            </tr>
            <tr>
                <th>Nama Pengaju</th>
                <td>{{ $detail->nama ?? 'Tidak ada nama' }}</td>
            </tr>
            <tr>
                <td>Jenis Pengajuan</td>
                <td>{{ $pengajuan->kategoriPengajuan->nama_kategori }}</td>
            </tr>
            <tr>
                <th>Tanggal Pengajuan</th>
                <td>{{ $detail->created_at }}</td>
            </tr>
            <tr>
                <th>Tanggal Disahkan</th>
                <td>{{ $detail->updated_at }}</td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>{{ $pengajuan->statusPengajuan->status }}</td>
            </tr>
            <tr>
                <th>Admin pengelola</th>
                <td>{{ $pengajuan->admin->name ?? 'Tidak ada admin' }}</td>
            </tr>
            <tr>
                <th>Ditanda tangani oleh:</th>
                <td>{{ $pengajuan->kuwuUpdated->nama ?? 'Tidak ada kuwu' }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
