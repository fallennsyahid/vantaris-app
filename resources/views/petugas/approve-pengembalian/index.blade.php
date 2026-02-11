<x-app-layout title="Penyetujuan Pengembalian">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Menunggu Pengembalian
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-primary flex justify-center items-center">
                        <i class="fas fa-briefcase text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalMenunggu }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Alat Dikembalikan
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-green-600 flex justify-center items-center">
                        <i class="fas fa-circle-check text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalKembali }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Terlambat
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-orange-600 flex justify-center items-center">
                        <i class="fas fa-exclamation-triangle text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalTerlambat }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Pengguna Diblokir
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex justify-center items-center">
                        <i class="fas fa-circle-xmark text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalUserBlokir }}
                </div>
            </div>
        </div>

        <!-- QR Scanner Button -->
        <div class="mb-6">
            <button type="button" id="btnScanQR"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                <i class="fas fa-qrcode mr-2"></i>Scan QR Code Pengembalian
            </button>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px" id="pengembalianTab" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-blue-600 text-blue-600 font-semibold tab-button active"
                        id="menunggu-tab" data-tab="menunggu" type="button">
                        Menunggu Pengembalian ({{ $peminjamanDiambil->count() }})
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 font-semibold tab-button"
                        id="selesai-tab" data-tab="selesai" type="button">
                        History ({{ $peminjamanDikembalikan->count() }})
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div id="pengembalianTabContent">
            <!-- Tab Menunggu Pengembalian -->
            <div class="tab-content" id="menunggu" role="tabpanel">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h6 class="text-lg font-semibold text-gray-900">Daftar Peminjaman Menunggu Pengembalian</h6>
                    </div>
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Kode</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Peminjam</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Tanggal Pinjam</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Tanggal Rencana Kembali</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Status Keterlambatan</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($peminjamanDiambil as $peminjaman)
                                        @php
                                            $isLate = now()->greaterThan($peminjaman->tanggal_pengembalian_rencana);
                                            $daysLate = $isLate
                                                ? now()->diffInDays($peminjaman->tanggal_pengembalian_rencana)
                                                : 0;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">{{ $loop->iteration }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $peminjaman->kode_peminjaman }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <h6 class="text-sm font-semibold text-gray-900">
                                                        {{ $peminjaman->peminjam->nama_lengkap ?? $peminjaman->peminjam->name }}
                                                    </h6>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $peminjaman->peminjam->email }}</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $peminjaman->tanggal_pengajuan->format('d/m/Y') }}</p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $peminjaman->tanggal_pengembalian_rencana->format('d/m/Y') }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($isLate)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white">Terlambat
                                                        {{ $daysLate }} hari</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-500 to-green-600 text-white">Tepat
                                                        Waktu</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <a href="{{ route('petugas.approve-pengembalian.show', $peminjaman->peminjaman_id) }}"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                                    title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center">
                                                <p class="text-sm text-gray-500">Tidak ada peminjaman yang
                                                    menunggu pengembalian</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab History -->
            <div class="tab-content hidden" id="selesai" role="tabpanel">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h6 class="text-lg font-semibold text-gray-900">History Pengembalian</h6>
                    </div>
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Kode</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Peminjam</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Tanggal Kembali</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Kondisi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($peminjamanDikembalikan as $peminjaman)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">{{ $loop->iteration }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $peminjaman->kode_peminjaman }}</p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $peminjaman->peminjam->nama_lengkap ?? $peminjaman->peminjam->name }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $peminjaman->pengembalian?->tanggal_pengembalian_sebenarnya?->format('d/m/Y H:i') ?? '-' }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($peminjaman->pengembalian)
                                                    @php
                                                        $kondisi = is_object($peminjaman->pengembalian->kondisi)
                                                            ? $peminjaman->pengembalian->kondisi->value
                                                            : $peminjaman->pengembalian->kondisi;
                                                        $badgeClass = match ($kondisi) {
                                                            'baik' => 'from-green-500 to-green-600',
                                                            'rusak' => 'from-red-500 to-red-600',
                                                            'tidak_lengkap' => 'from-orange-500 to-orange-600',
                                                            'hilang' => 'from-gray-600 to-gray-800',
                                                            default => 'from-gray-400 to-gray-600',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r {{ $badgeClass }} text-white">
                                                        {{ ucwords(str_replace('_', ' ', $kondisi)) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statusValue = is_object($peminjaman->status)
                                                        ? $peminjaman->status->value
                                                        : $peminjaman->status;
                                                @endphp
                                                @if ($statusValue === 'kembali')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-green-500 to-green-600 text-white">Dikembalikan</span>
                                                @elseif($statusValue === 'terlambat')
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-red-500 to-red-600 text-white">Terlambat</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <a href="{{ route('petugas.approve-pengembalian.show', $peminjaman->peminjaman_id) }}"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                                    title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center">
                                                <p class="text-sm text-gray-500">Belum ada history
                                                    pengembalian</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Scanner Modal -->
    <div class="hidden fixed inset-0" id="qrScannerModal" tabindex="-1" aria-hidden="true"
        style="z-index: 99999 !important;">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal content wrapper -->
        <div class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative z-10">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Scan QR Code Pengembalian
                            </h3>
                            <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal"
                                aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            <div id="qr-reader" class="w-full"></div>
                            <div id="qr-reader-results" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal untuk Pengembalian -->
    <div class="hidden fixed inset-0" id="returnConfirmModal" tabindex="-1" aria-hidden="true"
        style="z-index: 99999">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

        <!-- Modal content wrapper -->
        <div class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                    <form id="returnForm">
                        @csrf
                        <input type="hidden" name="peminjaman_id" id="return_peminjaman_id">

                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Pengembalian</h3>
                                <button type="button" class="text-gray-400 hover:text-gray-500"
                                    data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div id="returnDetailsContent" class="mb-4">
                                <!-- Content will be filled by JavaScript -->
                            </div>

                            <div class="mb-4">
                                <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">Kondisi
                                    Alat <span class="text-red-600">*</span></label>
                                <select
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    id="kondisi" name="kondisi" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="tidak_lengkap">Tidak Lengkap</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan
                                    <span id="catatanRequired" class="text-red-600 hidden">*</span>
                                    <span id="catatanOptional" class="text-gray-500 text-xs">(opsional jika kondisi
                                        baik)</span>
                                </label>
                                <textarea
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    id="catatan" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                <p id="catatanHelp" class="text-xs text-gray-500 mt-1 hidden">Catatan wajib diisi jika
                                    kondisi alat bukan "Baik"</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-200">
                                Proses Pengembalian
                            </button>
                            <button type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200"
                                data-bs-dismiss="modal">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Include html5-qrcode library -->
        <script src="https://unpkg.com/html5-qrcode"></script>

        <!-- Setup routes for external JS -->
        <script>
            window.appRoutes = {
                scanProses: "{{ route('petugas.pengembalian.scan-proses') }}",
                proses: "{{ route('petugas.pengembalian.proses') }}"
            };
            console.log('Routes configured:', window.appRoutes);
        </script>

        <!-- Load external JS file -->
        <script src="{{ asset('petugas/approval-pengembalian/index.js') }}"></script>
    @endpush
</x-app-layout>
