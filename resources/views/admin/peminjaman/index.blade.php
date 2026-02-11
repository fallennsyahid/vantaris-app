<x-app-layout title="Data Peminjaman">
    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Data Peminjaman</h1>
                <p class="text-text font-lato">Lihat daftar peminjaman yang telah dilakukan.</p>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 ">
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Peminjaman
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-primary flex justify-center items-center">
                        <i class="fas fa-briefcase text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalPeminjaman }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Jumlah Peminjam
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-green-600 flex justify-center items-center">
                        <i class="fas fa-circle-check text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalPeminjam }}
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm mt-8">
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
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                                Peminjam</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Pengajuan</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Alat yang
                                Dipinjam</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Rencana</th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamans as $index => $peminjaman)
                            <tr class="hover:bg-gray-50/80 transition-colors border-b border-gray-100">
                                <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
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
