<x-app-layout title="Peminjaman Alat">

    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Pengajuan Peminjaman</h1>
                <p class="text-text font-lato">Ajukan peminjaman alat di sini.</p>
            </div>
            <div>
                <button type="button" id="open-modal"
                    class="flex items-center gap-3 text-white font-medium px-6 py-3 rounded-lg bg-linear-to-r from-primary to-secondary cursor-pointer hover:from-secondary hover:to-primary shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus"></i>
                    Ajukan Peminjaman Alat
                </button>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Pengajuan Peminjaman
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
                        Pengajuan Disetujui
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
                        Pengajuan Pending
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
                        Pengajuan Ditolak
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
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                No</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Pengajuan</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Alat yang
                                Dipinjam</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Rencana</th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Status</th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $index => $peminjaman)
                            <tr class="hover:bg-gray-50/80 transition-colors border-b border-gray-100">
                                <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengajuan)->format('H:i') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        @foreach ($peminjaman->details->take(2) as $detail)
                                            <span class="text-sm text-gray-700">
                                                • {{ $detail->alat->nama_alat }} ({{ $detail->jumlah }}x)
                                            </span>
                                        @endforeach
                                        @if ($peminjaman->details->count() > 2)
                                            <span class="text-xs text-primary">+{{ $peminjaman->details->count() - 2 }}
                                                lainnya</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500">Ambil:</span>
                                        <span
                                            class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengambilan_rencana)->format('d M Y') }}</span>
                                        <span class="text-xs text-gray-500 mt-1">Kembali:</span>
                                        <span
                                            class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian_rencana)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => [
                                                'bg' => 'bg-orange-50',
                                                'text' => 'text-orange-700',
                                                'border' => 'border-orange-100',
                                                'label' => 'Pending',
                                            ],
                                            'disetujui' => [
                                                'bg' => 'bg-green-50',
                                                'text' => 'text-green-700',
                                                'border' => 'border-green-100',
                                                'label' => 'Disetujui',
                                            ],
                                            'ditolak' => [
                                                'bg' => 'bg-red-50',
                                                'text' => 'text-red-700',
                                                'border' => 'border-red-100',
                                                'label' => 'Ditolak',
                                            ],
                                            'diambil' => [
                                                'bg' => 'bg-blue-50',
                                                'text' => 'text-blue-700',
                                                'border' => 'border-blue-100',
                                                'label' => 'Diambil',
                                            ],
                                            'kembali' => [
                                                'bg' => 'bg-gray-50',
                                                'text' => 'text-gray-700',
                                                'border' => 'border-gray-100',
                                                'label' => 'Kembali',
                                            ],
                                            'terlambat' => [
                                                'bg' => 'bg-purple-50',
                                                'text' => 'text-purple-700',
                                                'border' => 'border-purple-100',
                                                'label' => 'Terlambat',
                                            ],
                                        ];
                                        $status = $statusConfig[$peminjaman->status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium {{ $status['bg'] }} {{ $status['text'] }} border {{ $status['border'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        <a href="{{ route('peminjam.peminjaman.show', $peminjaman->peminjaman_id) }}"
                                            class="text-primary hover:text-primary/80 transition-colors"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($peminjaman->status === 'pending')
                                            <form
                                                action="{{ route('peminjam.peminjaman.destroy', $peminjaman->peminjaman_id) }}"
                                                method="POST" class="delete-form inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition-colors"
                                                    title="Batalkan">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">Belum ada pengajuan peminjaman</p>
                                        <p class="text-sm">Klik tombol "Ajukan Peminjaman Alat" untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>

<!-- Modal Create Peminjaman -->
<div id="create-new-petugas" class="fixed inset-0 z-99999 hidden items-center justify-center p-4 animate-fade-in">
    <div class="close-modal absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="bg-white max-w-4xl w-full rounded-xl shadow-2xl relative border border-white/20 overflow-hidden">
        <div class="bg-linear-to-r from-heading via-primary to-secondary p-8 text-center overflow-hidden relative">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 rounded-full flex justify-center items-center text-white mx-auto mb-4 backdrop-blur-sm">
                    <i class="fas fa-clipboard-list text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Ajukan Peminjaman Alat</h1>
                <p class="text-white/90 text-base font-lato">Isi form berikut untuk mengajukan peminjaman alat</p>
            </div>

            <button
                class="close-modal absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white cursor-pointer transition-all duration-300 hover:rotate-90">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
            <form action="{{ route('peminjam.peminjaman.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="tanggal_pengambilan_rencana"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                            <i class="fas fa-calendar-check"></i>
                            Tanggal Pengambilan <span class="text-red-400">*</span>
                        </label>
                        <input type="date" id="tanggal_pengambilan_rencana" name="tanggal_pengambilan_rencana"
                            required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            value="{{ old('tanggal_pengambilan_rencana') }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200">
                        @error('tanggal_pengambilan_rencana')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label for="tanggal_pengembalian_rencana"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                            <i class="fas fa-calendar-times"></i>
                            Tanggal Pengembalian <span class="text-red-400">*</span>
                        </label>
                        <input type="date" id="tanggal_pengembalian_rencana" name="tanggal_pengembalian_rencana"
                            required min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                            value="{{ old('tanggal_pengembalian_rencana') }}"
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200">
                        @error('tanggal_pengembalian_rencana')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="group">
                    <label for="alasan_meminjam"
                        class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                        <i class="fas fa-comment-alt"></i>
                        Alasan Meminjam <span class="text-red-400">*</span>
                    </label>
                    <textarea id="alasan_meminjam" name="alasan_meminjam" rows="3" required
                        class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                        placeholder="Jelaskan alasan dan keperluan peminjaman alat minimal 10 karakter">{{ old('alasan_meminjam') }}</textarea>
                    @error('alasan_meminjam')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="group">
                    <label class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-3">
                        <i class="fas fa-tools"></i>
                        Pilih Alat yang Dipinjam <span class="text-red-400">*</span>
                    </label>

                    <div id="alat-container" class="space-y-3">
                        <!-- Alat items will be added here -->
                    </div>

                    <button type="button" id="add-alat"
                        class="mt-3 w-full px-4 py-2 border-2 border-dashed border-primary/30 text-primary rounded-lg hover:bg-primary/5 transition-all">
                        <i class="fas fa-plus mr-2"></i>Tambah Alat
                    </button>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="button"
                        class="close-modal flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    <button type="submit"
                        class="px-4 flex-1 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/30 cursor-pointer transition-all">
                        <i class="fas fa-paper-plane mr-2"></i> Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Yakin ingin membatalkan?',
                text: 'Pengajuan peminjaman akan dibatalkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Data alat dari backend
    const alats = @json($alats);
    let alatCounter = 0;

    function createAlatItem() {
        const alatItem = document.createElement('div');
        alatItem.className = 'alat-item grid grid-cols-12 gap-3 p-4 bg-slate-50 rounded-lg border border-gray-200';
        alatItem.innerHTML = `
            <div class="col-span-12 md:col-span-7">
                <select name="alat[${alatCounter}][alat_id]" required 
                    class="w-full px-3 py-2 bg-white border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary text-sm">
                    <option value="">Pilih Alat</option>
                    ${alats.map(alat => `
                        <option value="${alat.alat_id}" data-stok="${alat.stok}">
                            ${alat.nama_alat} (Stok: ${alat.stok})
                        </option>
                    `).join('')}
                </select>
            </div>
            <div class="col-span-10 md:col-span-4">
                <input type="number" name="alat[${alatCounter}][jumlah]" min="1" value="1" required
                    placeholder="Jumlah"
                    class="w-full px-3 py-2 bg-white border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary text-sm">
            </div>
            <div class="col-span-2 md:col-span-1 flex items-center justify-center">
                <button type="button" class="remove-alat text-red-600 hover:text-red-800 text-lg">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        alatCounter++;
        return alatItem;
    }

    document.getElementById('add-alat').addEventListener('click', function() {
        const container = document.getElementById('alat-container');
        const alatItem = createAlatItem();
        container.appendChild(alatItem);

        // Add remove event
        alatItem.querySelector('.remove-alat').addEventListener('click', function() {
            if (container.children.length > 1) {
                alatItem.remove();
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Minimal harus ada 1 alat yang dipilih',
                    showConfirmButton: true,
                });
            }
        });
    });

    // Add first alat item on page load
    document.getElementById('add-alat').click();
</script>

<script src="{{ asset('asset-peminjam/js/index.js') }}"></script>
