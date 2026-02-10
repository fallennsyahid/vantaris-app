<x-app-layout title="Detail Pengajuan Peminjaman">

    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Detail Pengajuan Peminjaman</h1>
                <p class="text-text font-lato">Informasi lengkap pengajuan peminjaman alat.</p>
            </div>
            <div>
                <a href="{{ route('petugas.approve-peminjaman.index') }}"
                    class="flex items-center gap-2 text-gray-700 font-medium px-6 py-3 rounded-lg border border-gray-300 hover:bg-gray-100 cursor-pointer transition-all duration-200">
                    <i class="fas fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Card -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-info-circle text-gray-400"></i>
                            Status Peminjaman
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
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
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                    <i class="fas {{ $config['icon'] }} text-lg"></i>
                                    {{ $config['label'] }}
                                </span>
                            </div>
                            @if ($peminjaman->status === 'pending')
                                <div class="flex gap-2">
                                    <button type="button"
                                        onclick="openApproveModal('{{ $peminjaman->peminjaman_id }}')"
                                        class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition-colors cursor-pointer">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                    <button type="button" onclick="openRejectModal('{{ $peminjaman->peminjaman_id }}')"
                                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-colors cursor-pointer">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </div>
                            @endif
                        </div>

                        @if ($peminjaman->note)
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-comment-alt text-blue-600 mt-1"></i>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Catatan:</p>
                                        <p class="text-sm text-blue-700 mt-1">{{ $peminjaman->note }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Alat yang Dipinjam -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-tools text-gray-400"></i>
                            Alat yang Dipinjam
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach ($peminjaman->details as $detail)
                                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div
                                        class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fas fa-box text-primary text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900 mb-1">{{ $detail->alat->nama_alat }}</h3>
                                        <div class="flex flex-wrap gap-3 text-sm text-gray-600">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-tag text-gray-400"></i>
                                                {{ $detail->alat->kategori->nama_kategori }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-cube text-gray-400"></i>
                                                Jumlah: {{ $detail->jumlah }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-warehouse text-gray-400"></i>
                                                Stok tersedia: {{ $detail->alat->stok }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Alasan Peminjaman -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-comments text-gray-400"></i>
                            Alasan Peminjaman
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 leading-relaxed">{{ $peminjaman->alasan_meminjam }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informasi Peminjam -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-user text-gray-400"></i>
                            Informasi Peminjam
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                                {{-- {{ strtoupper(substr($peminjaman->peminjam->name, 0, 2)) }} --}}
                                <img src="{{ Avatar::create($peminjaman->peminjam->name_lengkap ?? ($peminjaman->peminjam->name ?? 'User'))->toBase64() }}"
                                    alt="{{ $peminjaman->peminjam->name }}" class="rounded-full w-12 h-12">
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $peminjaman->peminjam->name }}</h3>
                                <p class="text-sm text-gray-500">{{ ucfirst($peminjaman->peminjam->role->value) }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-envelope w-4"></i>
                                <span>{{ $peminjaman->peminjam->email }}</span>
                            </div>
                            @if ($peminjaman->peminjam->phone)
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fas fa-phone w-4"></i>
                                    <span>{{ $peminjaman->peminjam->phone }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-gray-400"></i>
                            Timeline
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                    <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Tanggal Pengajuan</p>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($peminjaman->created_at)->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                    <i class="fas fa-calendar-day text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Rencana Pengambilan</p>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengambilan_rencana)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                                    <i class="fas fa-calendar-check text-orange-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Rencana Pengembalian</p>
                                    <p class="text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengembalian_rencana)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            @if ($peminjaman->tanggal_pengambilan_sebenarnya)
                                <div class="flex gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                                        <i class="fas fa-check text-purple-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Tanggal Pengambilan Sebenarnya</p>
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($peminjaman->tanggal_pengambilan_sebenarnya)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($peminjaman->pemberi_izin)
                    <!-- Informasi Petugas -->
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-user-tie text-gray-400"></i>
                                Diproses Oleh
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary font-bold">
                                    {{-- {{ strtoupper(substr($peminjaman->pemberi_izin->name, 0, 2)) }} --}}
                                    <img src="{{ Avatar::create($peminjaman->pemberi_izin->nama_lengkap ?? ($peminjaman->pemberi_izin->name ?? 'User'))->toBase64() }}"
                                        alt="{{ $peminjaman->pemberi_izin->nama_lengkap ?? 'Guest' }}"
                                        class="rounded-full w-10 h-10">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm">
                                        {{ $peminjaman->pemberi_izin->nama_lengkap ?? $peminjaman->pemberi_izin->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        {{ ucfirst($peminjaman->pemberi_izin->role->value) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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
</script>

<script src="{{ asset('asset-peminjam/js/index.js') }}"></script>
