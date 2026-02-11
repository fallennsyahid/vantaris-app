<x-app-layout title="Detail Peminjaman">

    <div class="pt-3">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('peminjam.peminjaman.index') }}"
                class="inline-flex items-center gap-2 text-gray-600 hover:text-primary transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Peminjaman</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Detail Peminjaman -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 bg-linear-to-r from-primary/5 to-secondary/5">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary"></i>
                            Informasi Peminjaman
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Status -->
                        <div class="flex items-center justify-between pb-4 border-b">
                            <span class="text-sm font-medium text-gray-600">Status Peminjaman</span>
                            @php
                                $statusConfig = [
                                    'pending' => [
                                        'bg' => 'bg-orange-100',
                                        'text' => 'text-orange-700',
                                        'icon' => 'fa-clock',
                                        'label' => 'Menunggu Persetujuan',
                                    ],
                                    'disetujui' => [
                                        'bg' => 'bg-green-100',
                                        'text' => 'text-green-700',
                                        'icon' => 'fa-check-circle',
                                        'label' => 'Disetujui',
                                    ],
                                    'ditolak' => [
                                        'bg' => 'bg-red-100',
                                        'text' => 'text-red-700',
                                        'icon' => 'fa-times-circle',
                                        'label' => 'Ditolak',
                                    ],
                                    'diambil' => [
                                        'bg' => 'bg-blue-100',
                                        'text' => 'text-blue-700',
                                        'icon' => 'fa-hands',
                                        'label' => 'Sedang Dipinjam',
                                    ],
                                    'kembali' => [
                                        'bg' => 'bg-gray-100',
                                        'text' => 'text-gray-700',
                                        'icon' => 'fa-check-double',
                                        'label' => 'Telah Dikembalikan',
                                    ],
                                    'terlambat' => [
                                        'bg' => 'bg-purple-100',
                                        'text' => 'text-purple-700',
                                        'icon' => 'fa-exclamation-triangle',
                                        'label' => 'Terlambat',
                                    ],
                                ];
                                $status = $statusConfig[$peminjaman->status] ?? $statusConfig['pending'];
                            @endphp
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }} gap-2">
                                <i class="fas {{ $status['icon'] }}"></i>
                                {{ $status['label'] }}
                            </span>
                        </div>

                        <!-- Tanggal Pengajuan -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                                <p class="text-base font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->format('d F Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Peminjam</p>
                                <p class="text-base font-semibold text-gray-800">
                                    {{ $peminjaman->peminjam->nama_lengkap }}
                                </p>
                            </div>
                        </div>

                        <!-- Tanggal Rencana -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <p class="text-sm text-blue-600 mb-1 flex items-center gap-2">
                                    <i class="fas fa-calendar-check"></i>
                                    Rencana Pengambilan
                                </p>
                                <p class="text-lg font-bold text-blue-800">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengambilan_rencana)->format('d F Y') }}
                                </p>
                            </div>
                            <div class="p-4 bg-orange-50 rounded-lg">
                                <p class="text-sm text-orange-600 mb-1 flex items-center gap-2">
                                    <i class="fas fa-calendar-times"></i>
                                    Rencana Pengembalian
                                </p>
                                <p class="text-lg font-bold text-orange-800">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian_rencana)->format('d F Y') }}
                                </p>
                            </div>
                        </div>

                        @if ($peminjaman->tanggal_pengambilan_sebenarnya)
                            <div class="p-4 bg-green-50 rounded-lg">
                                <p class="text-sm text-green-600 mb-1 flex items-center gap-2">
                                    <i class="fas fa-calendar-check"></i>
                                    Tanggal Pengambilan Aktual
                                </p>
                                <p class="text-base font-semibold text-green-800">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengambilan_sebenarnya)->format('d F Y, H:i') }}
                                </p>
                            </div>
                        @endif

                        <!-- Alasan Meminjam -->
                        <div class="pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-2 flex items-center gap-2">
                                <i class="fas fa-comment-alt"></i>
                                Alasan Meminjam
                            </p>
                            <p class="text-base text-gray-800 bg-gray-50 p-4 rounded-lg">
                                {{ $peminjaman->alasan_meminjam }}
                            </p>
                        </div>

                        @if ($peminjaman->note && $peminjaman->status === 'ditolak')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600 mb-2 flex items-center gap-2 font-semibold">
                                    <i class="fas fa-info-circle"></i>
                                    Alasan Penolakan
                                </p>
                                <p class="text-base text-red-800">
                                    {{ $peminjaman->note }}
                                </p>
                            </div>
                        @endif

                        @if ($peminjaman->pemberi_izin)
                            <div class="pt-4 border-t">
                                <p class="text-sm text-gray-500 mb-2">Disetujui oleh</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                        <img src="{{ Avatar::create($peminjaman->pemberi_izin->nama_lengkap ?? ($peminjaman->pemberi_izin->name ?? 'User'))->toBase64() }}"
                                            alt="{{ $peminjaman->pemberi_izin->nama_lengkap ?? 'Guest' }}"
                                            class="rounded-full w-10 h-10">
                                        {{-- <span
                                            class="text-primary font-bold">{{ substr($peminjaman->pemberi_izin->nama_lengkap, 0, 1) }}</span> --}}
                                    </div>
                                    <div>
                                        <p class="text-base font-semibold text-gray-800">
                                            {{ $peminjaman->pemberi_izin->nama_lengkap }}</p>
                                        <p class="text-sm text-gray-500">{{ $peminjaman->pemberi_izin->email }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Daftar Alat yang Dipinjam -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100 bg-linear-to-r from-primary/5 to-secondary/5">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-tools text-primary"></i>
                            Daftar Alat yang Dipinjam
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($peminjaman->details as $detail)
                                <div
                                    class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div
                                        class="w-16 h-16 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                                        @if ($detail->alat->foto_alat)
                                            <img src="{{ asset('storage/' . $detail->alat->foto_alat) }}"
                                                alt="{{ $detail->alat->nama_alat }}"
                                                class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <i class="fas fa-tools text-gray-400 text-2xl"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-800">
                                            {{ $detail->alat->nama_alat }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $detail->alat->kategori->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">Jumlah</p>
                                        <p class="text-2xl font-bold text-primary">{{ $detail->jumlah }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Code Sidebar -->
            <div class="lg:col-span-1">
                @if (in_array($peminjaman->status, ['disetujui', 'diambil']) && $qrCode)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm sticky top-4">
                        <div
                            class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r {{ $peminjaman->status === 'diambil' ? 'from-blue-500 to-blue-600' : 'from-green-500 to-green-600' }} text-white">
                            <h2 class="text-lg font-semibold flex items-center gap-2">
                                <i class="fas fa-qrcode"></i>
                                @if ($peminjaman->status === 'diambil')
                                    QR Code Pengembalian
                                @else
                                    QR Code Pengambilan
                                @endif
                            </h2>
                            <p
                                class="text-sm {{ $peminjaman->status === 'diambil' ? 'text-blue-50' : 'text-green-50' }} mt-1">
                                @if ($peminjaman->status === 'diambil')
                                    Tunjukkan kode ini saat pengembalian alat
                                @else
                                    Tunjukkan kode ini saat pengambilan alat
                                @endif
                            </p>
                        </div>

                        <div class="p-6">
                            <div
                                class="bg-white border-4 {{ $peminjaman->status === 'diambil' ? 'border-blue-500' : 'border-green-500' }} rounded-lg p-4 mb-4">
                                <div class="flex justify-center">
                                    <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code"
                                        class="w-full max-w-75">
                                </div>
                            </div>

                            <div
                                class="{{ $peminjaman->status === 'diambil' ? 'bg-blue-50 border-blue-200' : 'bg-green-50 border-green-200' }} border rounded-lg p-4 mb-4">
                                <p
                                    class="text-xs {{ $peminjaman->status === 'diambil' ? 'text-blue-800' : 'text-green-800' }} font-medium mb-2">
                                    Token:</p>
                                <p
                                    class="text-sm {{ $peminjaman->status === 'diambil' ? 'text-blue-900' : 'text-green-900' }} font-mono break-all">
                                    {{ $peminjaman->qr_token }}</p>
                            </div>

                            <div class="space-y-2">
                                <button onclick="downloadQR()"
                                    class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-download"></i>
                                    Download QR Code
                                </button>
                                <button onclick="printQR()"
                                    class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-print"></i>
                                    Print QR Code
                                </button>
                            </div>

                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <p class="text-xs text-blue-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Catatan:</strong>
                                    @if ($peminjaman->status === 'diambil')
                                        Simpan atau cetak QR Code ini untuk memudahkan proses pengembalian alat.
                                    @else
                                        Simpan atau cetak QR Code ini untuk memudahkan proses pengambilan alat.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif ($peminjaman->status === 'pending')
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                        <div class="text-center">
                            <i class="fas fa-hourglass-half text-4xl text-orange-500 mb-4"></i>
                            <h3 class="text-lg font-semibold text-orange-800 mb-2">Menunggu Persetujuan</h3>
                            <p class="text-sm text-orange-700">
                                Pengajuan Anda sedang dalam proses review oleh petugas. QR Code akan tersedia setelah
                                pengajuan disetujui.
                            </p>
                        </div>
                    </div>
                @elseif ($peminjaman->status === 'ditolak')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="text-center">
                            <i class="fas fa-times-circle text-4xl text-red-500 mb-4"></i>
                            <h3 class="text-lg font-semibold text-red-800 mb-2">Pengajuan Ditolak</h3>
                            <p class="text-sm text-red-700">
                                Maaf, pengajuan peminjaman Anda tidak dapat disetujui.
                            </p>
                        </div>
                    </div>
                @elseif ($peminjaman->status === 'diambil')
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="text-center">
                            <i class="fas fa-hands text-4xl text-blue-500 mb-4"></i>
                            <h3 class="text-lg font-semibold text-blue-800 mb-2">Alat Sedang Dipinjam</h3>
                            <p class="text-sm text-blue-700">
                                Alat telah Anda ambil. Jangan lupa kembalikan sesuai jadwal yang telah ditentukan.
                            </p>
                        </div>
                    </div>
                @elseif ($peminjaman->status === 'kembali')
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="text-center">
                            <i class="fas fa-check-double text-4xl text-gray-500 mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Peminjaman Selesai</h3>
                            <p class="text-sm text-gray-700">
                                Terima kasih telah mengembalikan alat tepat waktu.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function downloadQR() {
            const qrImage = document.querySelector('img[alt="QR Code"]');
            const link = document.createElement('a');
            link.href = qrImage.src;
            link.download = 'qr-code-peminjaman-{{ $peminjaman->peminjaman_id }}.svg';
            link.click();
        }

        function printQR() {
            const qrImage = document.querySelector('img[alt="QR Code"]');
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Print QR Code</title>');
            printWindow.document.write('<style>body { text-align: center; padding: 20px; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h2>QR Code Peminjaman Alat</h2>');
            printWindow.document.write('<p>ID: {{ $peminjaman->peminjaman_id }}</p>');
            printWindow.document.write('<img src="' + qrImage.src + '" style="max-width: 400px;">');
            printWindow.document.write('<p>Token: {{ $peminjaman->qr_token }}</p>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

</x-app-layout>
