<x-app-layout title="Detail Pengembalian">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="{{ route('petugas.approve-pengembalian.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h6 class="text-lg font-semibold text-gray-900">Detail Peminjaman</h6>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Kode Peminjaman:</p>
                                <p class="text-sm text-gray-600">{{ $peminjaman->kode_peminjaman }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Status:</p>
                                @php
                                    $statusValue = is_object($peminjaman->status)
                                        ? $peminjaman->status->value
                                        : $peminjaman->status;
                                    $badgeClass = match ($statusValue) {
                                        'pending' => 'from-yellow-500 to-yellow-600',
                                        'disetujui' => 'from-blue-500 to-blue-600',
                                        'diambil' => 'from-indigo-500 to-indigo-600',
                                        'kembali' => 'from-green-500 to-green-600',
                                        'terlambat' => 'from-red-500 to-red-600',
                                        'ditolak' => 'from-gray-600 to-gray-800',
                                        default => 'from-gray-400 to-gray-600',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r {{ $badgeClass }} text-white">
                                    {{ ucfirst($statusValue) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Tanggal Pengajuan:</p>
                                <p class="text-sm text-gray-600">{{ $peminjaman->tanggal_pengajuan->format('d/m/Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Tanggal Rencana Kembali:</p>
                                <p class="text-sm text-gray-600">
                                    {{ $peminjaman->tanggal_pengembalian_rencana->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        @if ($peminjaman->pengembalian)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 mb-1">Tanggal Kembali Sebenarnya:</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $peminjaman->pengembalian->tanggal_pengembalian_sebenarnya->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 mb-1">Kondisi:</p>
                                    @php
                                        $kondisi = is_object($peminjaman->pengembalian->kondisi)
                                            ? $peminjaman->pengembalian->kondisi->value
                                            : $peminjaman->pengembalian->kondisi;
                                        $kondisiBadge = match ($kondisi) {
                                            'baik' => 'from-green-500 to-green-600',
                                            'rusak' => 'from-red-500 to-red-600',
                                            'tidak_lengkap' => 'from-orange-500 to-orange-600',
                                            'hilang' => 'from-gray-600 to-gray-800',
                                            default => 'from-gray-400 to-gray-600',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r {{ $kondisiBadge }} text-white">
                                        {{ ucwords(str_replace('_', ' ', $kondisi)) }}
                                    </span>
                                </div>
                            </div>

                            @if ($peminjaman->pengembalian->catatan)
                                <div class="mb-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-1">Catatan:</p>
                                    <p class="text-sm text-gray-600">{{ $peminjaman->pengembalian->catatan }}</p>
                                </div>
                            @endif
                        @endif

                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">Alasan Meminjam:</p>
                            <p class="text-sm text-gray-600">{{ $peminjaman->alasan_meminjam }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h6 class="text-lg font-semibold text-gray-900">Daftar Alat</h6>
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
                                            Nama Alat</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Kategori</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($peminjaman->details as $detail)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">{{ $loop->iteration }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm font-semibold text-gray-900">
                                                    {{ $detail->alat->nama_alat }}</p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm text-gray-600">
                                                    {{ $detail->alat->kategori->nama_kategori }}</p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <p class="text-sm font-semibold text-gray-900">{{ $detail->jumlah }}
                                                    unit</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h6 class="text-lg font-semibold text-gray-900">Informasi Peminjam</h6>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center mb-4">
                            @php
                                $avatarUrl = \Laravolt\Avatar\Facade::create(
                                    $peminjaman->peminjam->nama_lengkap ?? ($peminjaman->peminjam->name ?? 'User'),
                                )
                                    ->setDimension(80)
                                    ->setFontSize(32)
                                    ->toBase64();
                            @endphp
                            <img src="{{ $avatarUrl }}" class="rounded-full mr-3" alt="Avatar"
                                style="width: 60px; height: 60px;">
                            <div>
                                <h6 class="text-sm font-semibold text-gray-900 mb-1">
                                    {{ $peminjaman->peminjam->nama_lengkap ?? ($peminjaman->peminjam->name ?? 'User') }}
                                </h6>
                                <p class="text-xs text-gray-500">{{ $peminjaman->peminjam->email ?? 'Email' }}</p>
                            </div>
                        </div>

                        <hr class="border-gray-200 my-4">

                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-1">Role:</p>
                            @php
                                $roleValue = is_object($peminjaman->peminjam->role)
                                    ? $peminjaman->peminjam->role->value
                                    : $peminjaman->peminjam->role;
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                {{ ucfirst($roleValue) }}
                            </span>
                        </div>

                        @if ($peminjaman->peminjam->status_blokir)
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 mt-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-semibold text-red-700 mb-1">Status Akun:</p>
                                        <p class="text-xs text-red-700 mb-1">User ini sedang <strong>TERBLOKIR</strong>
                                        </p>
                                        @if ($peminjaman->peminjam->durasi_blokir)
                                            <p class="text-xs text-red-700 mb-0">Hingga:
                                                {{ \Carbon\Carbon::parse($peminjaman->peminjam->durasi_blokir)->format('d M Y, H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
