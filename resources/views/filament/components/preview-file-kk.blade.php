{{-- @if ($getState())
    <div class="mb-2 font-medium text-sm">File KK yang sudah diunggah:</div>

    @foreach ((array) $getState() as $file)
        @php
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $url = Storage::disk('public')->url($file);
        @endphp

        @if (in_array($ext, ['jpg', 'jpeg', 'png']))
            <img src="{{ $url }}" alt="File KK" class="w-64 rounded shadow mb-4" />
        @elseif ($ext === 'pdf')
            <a href="{{ $url }}" target="_blank" class="text-primary-600 underline block mb-2">Lihat file PDF</a>
        @else
            <span class="text-gray-500 block mb-2">File tidak dikenali</span>
        @endif
    @endforeach
@else
    <span class="text-gray-400 italic">Belum ada file diunggah</span>
@endif --}}
