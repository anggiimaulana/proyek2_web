<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Pengajuan</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col justify-between">

    <!-- Main Container -->
    <main class="flex-grow flex items-center justify-center px-4 py-6">
        <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 w-full max-w-4xl">

            <!-- Header: Logo & Title -->
            <div class="flex flex-col sm:flex-row items-center sm:justify-between border-b pb-4 mb-6">
                <div class="flex items-center gap-4 mb-4 sm:mb-0">
                    <img src="{{ asset('images/indramayu.png') }}" alt="Logo Desa" class="w-24 h-24 object-contain" />
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-700">Detail Pengajuan Surat Keterangan</h1>
                        <p class="text-base text-gray-500">Desa Bulak Lor, Kabupaten Indramayu</p>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="flex justify-center sm:justify-start mb-6">
                <span
                    class="text-green-600 text-sm font-semibold sm:text-base font-medium bg-green-100 px-4 py-1 rounded-full">
                    ✅ Pengajuan Terverifikasi
                </span>
            </div>



            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Nama Pengaju -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500">Nama Pengaju</p>
                    <p class="text-base font-medium">{{ $detail->nama ?? 'Tidak ada nama' }}</p>
                </div>

                <!-- Jenis Pengajuan -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500">Jenis Pengajuan</p>
                    <p class="text-base font-medium">{{ $pengajuan->kategoriPengajuan->nama_kategori }}</p>
                </div>

                <!-- Tanggal Pengajuan -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                    <p class="text-base font-medium">
                        {{ \Carbon\Carbon::parse($detail->created_at)->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>

                <!-- Tanggal Disahkan -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500">Tanggal Disahkan</p>
                    <p class="text-base font-medium">
                        {{ \Carbon\Carbon::parse($detail->updated_at)->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>


                <!-- Status -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500">Status</p>
                    <div class="flex justify-center sm:justify-start mt-1">
                        <span
                            class="text-blue-500 text-sm sm:text-base font-semibold bg-blue-100 px-3 py-1 rounded-full">
                            {{ $pengajuan->statusPengajuan->status }}
                        </span>
                    </div>
                </div>

                <!-- Admin Pengelola -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500">Admin Pengelola</p>
                    <p class="text-base font-medium">
                        {{ $pengajuan->adminUpdated->name ?? 'Tidak ada admin' }}
                    </p>
                </div>

                <!-- Ditandatangani oleh -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500">Ditandatangani oleh</p>
                    <p class="text-base font-medium">
                        {{ $pengajuan->kuwuUpdated->nama ?? 'Tidak ada kuwu' }}
                    </p>
                </div>

                <!-- Link Pengajuan -->
                <div class="text-center sm:text-left">
                    <p class="text-sm text-gray-500 mb-1">Link Detail Pengajuan</p>
                    <div class="flex flex-col items-center sm:items-start space-y-2">
                        <p id="urlText" class="text-blue-700 text-base break-words max-w-full">
                            127.0.0.1:8000/{{ $url }}
                        </p>
                        <button onclick="copyURL()"
                            class="bg-gray-200 hover:bg-gray-300 text-base px-3 py-1 rounded-md transition">
                            📋 Salin
                        </button>
                        <p id="notifCopied" class="text-green-600 text-xs hidden">Disalin!</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t text-center text-sm text-gray-500 py-4">
        <p class="text-base text-blue-500">
            &copy; {{ date('Y') }} Sistem Informasi Kota Cerdas<br>
            Politeknik Negeri Indramayu
        </p>
    </footer>

    <!-- Copy Script -->
    <script>
        function copyURL() {
            const text = "127.0.0.1:8000/{{ $url }}";
            navigator.clipboard.writeText(text).then(() => {
                const notif = document.getElementById("notifCopied");
                notif.classList.remove("hidden");
                setTimeout(() => {
                    notif.classList.add("hidden");
                }, 1500);
            });
        }
    </script>
</body>

</html>
