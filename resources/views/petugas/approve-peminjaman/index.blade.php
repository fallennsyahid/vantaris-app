<x-app-layout title="Penyetujuan Peminjaman">

    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Penyetujuan Peminjaman</h1>
                <p class="text-text font-lato">Kelola dan setujui pengajuan peminjaman alat.</p>
            </div>
            <div>
                <button type="button" id="open-scanner-btn"
                    class="flex items-center gap-3 text-white font-medium px-6 py-3 rounded-lg bg-linear-to-r from-blue-500 to-blue-700 cursor-pointer hover:from-blue-600 hover:to-blue-800 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-qrcode"></i>
                    Scan QR Code
                </button>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Pengajuan
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-primary flex justify-center items-center">
                        <i class="fas fa-briefcase text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalPengajuan }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Disetujui
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-green-600 flex justify-center items-center">
                        <i class="fas fa-circle-check text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $pengajuanDisetujui }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Menunggu Persetujuan
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-orange-600 flex justify-center items-center">
                        <i class="fas fa-exclamation-triangle text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $pengajuanPending }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Ditolak
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex justify-center items-center">
                        <i class="fas fa-circle-xmark text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $pengajuanDitolak }}
                </div>
            </div>
        </div>

        <!-- DataTable Section -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list text-gray-400"></i>
                    Daftar Pengajuan Peminjaman
                </h2>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                    Total: {{ $peminjamans->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table id="peminjaman-table" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peminjam
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Pengajuan</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Alat yang
                                Dipinjam</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Rencana</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $index => $peminjaman)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold text-xs">
                                            {{-- {{ strtoupper(substr($peminjaman->peminjam->name, 0, 2)) }} --}}
                                            <img src="{{ Avatar::create($peminjaman->peminjam->name_lengkap ?? ($peminjaman->peminjam->name ?? 'User'))->toBase64() }}"
                                                alt="{{ $peminjaman->peminjam->name }}" class="rounded-full w-8 h-8">
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $peminjaman->peminjam->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $peminjaman->peminjam->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @foreach ($peminjaman->details as $detail)
                                            <div class="text-sm text-gray-700">
                                                <span class="font-medium">{{ $detail->alat->nama_alat }}</span>
                                                <span class="text-gray-500">({{ $detail->jumlah }}x)</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700">
                                        <div class="flex items-center gap-1 mb-1">
                                            <i class="fas fa-calendar-day text-green-600 text-xs"></i>
                                            <span class="font-medium">Ambil:</span>
                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengambilan_rencana)->format('d M Y') }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="fas fa-calendar-check text-blue-600 text-xs"></i>
                                            <span class="font-medium">Kembali:</span>
                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian_rencana)->format('d M Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'pending' => [
                                                'bg' => 'bg-orange-100',
                                                'text' => 'text-orange-700',
                                                'icon' => 'fa-clock',
                                                'label' => 'Pending',
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
                                                'icon' => 'fa-hand-holding',
                                                'label' => 'Diambil',
                                            ],
                                            'kembali' => [
                                                'bg' => 'bg-gray-100',
                                                'text' => 'text-gray-700',
                                                'icon' => 'fa-check-double',
                                                'label' => 'Kembali',
                                            ],
                                        ];
                                        $config = $statusConfig[$peminjaman->status] ?? [
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-700',
                                            'icon' => 'fa-question',
                                            'label' => ucfirst($peminjaman->status),
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                        <i class="fas {{ $config['icon'] }}"></i>
                                        {{ $config['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('petugas.approve-peminjaman.show', $peminjaman->peminjaman_id) }}"
                                            class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transition-colors cursor-pointer">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                        @if ($peminjaman->status === 'pending')
                                            <button type="button"
                                                onclick="openApproveModal('{{ $peminjaman->peminjaman_id }}')"
                                                class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg transition-colors cursor-pointer">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                            <button type="button"
                                                onclick="openRejectModal('{{ $peminjaman->peminjaman_id }}')"
                                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition-colors cursor-pointer">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                    <p>Belum ada pengajuan peminjaman</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>

<!-- Modal Approve Peminjaman -->
<div id="approve-modal" class="fixed inset-0 z-99999 hidden items-center justify-center p-4 animate-fade-in">
    <div class="close-modal absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="bg-white max-w-md w-full rounded-xl shadow-2xl relative border border-white/20 overflow-hidden">
        <div class="bg-linear-to-r from-green-500 via-green-600 to-green-700 p-6 text-center overflow-hidden relative">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 rounded-full flex justify-center items-center text-white mx-auto mb-3 backdrop-blur-sm">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Setujui Peminjaman</h1>
                <p class="text-white/90 text-sm font-lato">Konfirmasi persetujuan peminjaman alat</p>
            </div>

            <button
                class="close-modal absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white cursor-pointer transition-all duration-300 hover:rotate-90">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6">
            <form id="approve-form" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <div class="group">
                        <label for="tanggal_pengambilan_sebenarnya"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2">
                            <i class="fas fa-calendar-day"></i>
                            Tanggal Pengambilan <span class="text-red-400">*</span>
                        </label>
                        <input type="date" id="tanggal_pengambilan_sebenarnya"
                            name="tanggal_pengambilan_sebenarnya" required
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                    </div>

                    <div class="group">
                        <label for="approve_note"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2">
                            <i class="fas fa-comment-alt"></i>
                            Catatan (Opsional)
                        </label>
                        <textarea id="approve_note" name="note" rows="3"
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                            placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button"
                            class="close-modal flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button type="submit"
                            class="px-4 flex-1 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 shadow-lg shadow-green-600/30 cursor-pointer transition-all">
                            <i class="fas fa-check mr-2"></i> Setujui
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject Peminjaman -->
<div id="reject-modal" class="fixed inset-0 z-99999 hidden items-center justify-center p-4 animate-fade-in">
    <div class="close-modal absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="bg-white max-w-md w-full rounded-xl shadow-2xl relative border border-white/20 overflow-hidden">
        <div class="bg-linear-to-r from-red-500 via-red-600 to-red-700 p-6 text-center overflow-hidden relative">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 rounded-full flex justify-center items-center text-white mx-auto mb-3 backdrop-blur-sm">
                    <i class="fas fa-times-circle text-3xl"></i>
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Tolak Peminjaman</h1>
                <p class="text-white/90 text-sm font-lato">Berikan alasan penolakan peminjaman</p>
            </div>

            <button
                class="close-modal absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white cursor-pointer transition-all duration-300 hover:rotate-90">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6">
            <form id="reject-form" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <div class="group">
                        <label for="reject_note"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2">
                            <i class="fas fa-comment-alt"></i>
                            Alasan Penolakan <span class="text-red-400">*</span>
                        </label>
                        <textarea id="reject_note" name="note" rows="4" required
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200"
                            placeholder="Jelaskan alasan penolakan minimal 10 karakter"></textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="button"
                            class="close-modal flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button type="submit"
                            class="px-4 flex-1 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/30 cursor-pointer transition-all">
                            <i class="fas fa-ban mr-2"></i> Tolak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal QR Scanner -->
<div id="scanner-modal" class="fixed inset-0 z-99999 hidden items-center justify-center p-4 animate-fade-in">
    <div class="close-scanner absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div
        class="bg-white max-w-2xl w-full rounded-xl shadow-2xl relative border border-white/20 overflow-hidden max-h-[90vh] flex flex-col">
        <div
            class="bg-linear-to-r from-blue-500 via-blue-600 to-blue-700 p-6 text-center overflow-hidden relative shrink-0">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 rounded-full flex justify-center items-center text-white mx-auto mb-3 backdrop-blur-sm">
                    <i class="fas fa-qrcode text-3xl"></i>
                </div>
                <h1 class="text-xl font-bold text-white mb-1">Scan QR Code Peminjaman</h1>
                <p class="text-white/90 text-sm font-lato">Arahkan kamera ke QR Code untuk memproses pengambilan alat
                </p>
            </div>

            <button
                class="close-scanner absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white cursor-pointer transition-all duration-300 hover:rotate-90">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <!-- Scanner Area -->
            <div id="qr-reader" class="w-full rounded-lg overflow-hidden border-2 border-gray-300"></div>

            <!-- Status Area -->
            <div id="scan-status" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                <div class="flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    <p class="text-sm text-blue-700">Mencari kamera...</p>
                </div>
            </div>

            <!-- Result Area -->
            <div id="scan-result" class="mt-4 hidden">
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 mb-2">QR Code terdeteksi:</p>
                    <p id="scan-result-text" class="text-sm text-gray-600 font-mono break-all"></p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="button"
                    class="close-scanner flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer">
                    <i class="fas fa-times mr-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CDN html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000,
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true,
        });
    </script>
@endif

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validasi Gagal!',
            html: '{!! implode('<br>', $errors->all()) !!}',
            showConfirmButton: true,
        });
    </script>
@endif

<script>
    // Modal handlers
    const approveModal = document.getElementById('approve-modal');
    const rejectModal = document.getElementById('reject-modal');
    const approveForm = document.getElementById('approve-form');
    const rejectForm = document.getElementById('reject-form');

    function openApproveModal(peminjamanId) {
        approveForm.action = `/petugas/approve-peminjaman/${peminjamanId}/approve`;
        approveModal.classList.remove('hidden');
        approveModal.classList.add('flex');

        // Set tanggal hari ini sebagai default
        document.getElementById('tanggal_pengambilan_sebenarnya').value = new Date().toISOString().split('T')[0];
    }

    function openRejectModal(peminjamanId) {
        rejectForm.action = `/petugas/approve-peminjaman/${peminjamanId}/reject`;
        rejectModal.classList.remove('hidden');
        rejectModal.classList.add('flex');
    }

    // Close modal handlers
    document.querySelectorAll('.close-modal').forEach(element => {
        element.addEventListener('click', function() {
            approveModal.classList.add('hidden');
            approveModal.classList.remove('flex');
            rejectModal.classList.add('hidden');
            rejectModal.classList.remove('flex');
        });
    });

    // QR Scanner handlers
    const scannerModal = document.getElementById('scanner-modal');
    const openScannerBtn = document.getElementById('open-scanner-btn');
    const scanStatus = document.getElementById('scan-status');
    const scanResult = document.getElementById('scan-result');
    const scanResultText = document.getElementById('scan-result-text');
    let html5QrcodeScanner = null;
    let isProcessing = false;
    let isScanning = false;

    // Open scanner modal
    openScannerBtn.addEventListener('click', function() {
        scannerModal.classList.remove('hidden');
        scannerModal.classList.add('flex');
        startScanner();
    });

    // Close scanner modal
    document.querySelectorAll('.close-scanner').forEach(element => {
        element.addEventListener('click', function() {
            stopScanner();
            scannerModal.classList.add('hidden');
            scannerModal.classList.remove('flex');
        });
    });

    // Start QR Scanner
    function startScanner() {
        if (html5QrcodeScanner) {
            return; // Scanner already running
        }

        scanStatus.classList.remove('hidden');
        scanResult.classList.add('hidden');
        isProcessing = false;
        isScanning = false;

        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                aspectRatio: 1.0,
                showTorchButtonIfSupported: true,
                showZoomSliderIfSupported: true
            },
            false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanError);

        // Update status and set scanning flag
        setTimeout(() => {
            scanStatus.querySelector('p').textContent = 'Kamera aktif. Arahkan ke QR Code...';
            isScanning = true;
        }, 1500);
    }

    // Stop QR Scanner
    function stopScanner() {
        if (html5QrcodeScanner) {
            try {
                html5QrcodeScanner.clear().then(() => {
                    html5QrcodeScanner = null;
                    isScanning = false;
                }).catch(error => {
                    console.error('Error stopping scanner:', error);
                    html5QrcodeScanner = null;
                    isScanning = false;
                });
            } catch (error) {
                console.error('Error clearing scanner:', error);
                html5QrcodeScanner = null;
                isScanning = false;
            }
        }
        scanStatus.classList.add('hidden');
        scanResult.classList.add('hidden');
    }

    // On scan success
    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) {
            return; // Prevent multiple scans
        }

        isProcessing = true;
        scanResultText.textContent = decodedText;
        scanResult.classList.remove('hidden');

        // Pause scanner safely
        if (html5QrcodeScanner && isScanning) {
            try {
                html5QrcodeScanner.pause(true);
                isScanning = false;
            } catch (error) {
                console.error('Error pausing scanner:', error);
            }
        }

        // Show loading
        Swal.fire({
            title: 'Memproses...',
            text: 'Memvalidasi QR Code',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Send to server via AJAX
        fetch('{{ route('petugas.peminjaman.scan-proses') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    qr_token: decodedText
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        html: `
                            <p class="text-gray-700">${data.message}</p>
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg text-left">
                                <p class="text-sm"><strong>Alat:</strong> ${data.data.alat}</p>
                            </div>
                        `,
                        showConfirmButton: true,
                        timer: 5000
                    }).then(() => {
                        // Reload page to refresh table
                        window.location.reload();
                    });

                    // Close scanner modal
                    stopScanner();
                    scannerModal.classList.add('hidden');
                    scannerModal.classList.remove('flex');
                } else {
                    // Error from server
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        showConfirmButton: true
                    });

                    // Resume scanner safely
                    if (html5QrcodeScanner && !isScanning) {
                        try {
                            html5QrcodeScanner.resume();
                            isScanning = true;
                        } catch (error) {
                            console.error('Error resuming scanner:', error);
                        }
                    }
                    isProcessing = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Gagal menghubungi server. Silakan coba lagi.',
                    showConfirmButton: true
                });

                // Resume scanner safely
                if (html5QrcodeScanner && !isScanning) {
                    try {
                        html5QrcodeScanner.resume();
                        isScanning = true;
                    } catch (error) {
                        console.error('Error resuming scanner:', error);
                    }
                }
                isProcessing = false;
            });
    }

    // On scan error (ignore)
    function onScanError(errorMessage) {
        // Ignore scan errors (happens continuously when no QR code is detected)
    }
</script>

<script src="{{ asset('asset-peminjam/js/index.js') }}"></script>
