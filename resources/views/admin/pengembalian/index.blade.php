<x-app-layout title="Data Pengembalian">
    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Data pengembalian</h1>
                <p class="text-text font-lato">Lihat daftar pengembalian yang telah dilakukan.</p>
            </div>
            <div>
                <button type="button" id="open-modal"
                    class="flex items-center gap-4 text-white font-medium px-5 py-3 rounded-lg bg-success cursor-pointer">
                    <i class="fas fa-file-export"></i>
                    Export Data Peminjaman
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 ">
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Pengembalian
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-primary flex justify-center items-center">
                        <i class="fas fa-undo text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalPengembalian }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Jumlah Peminjaman
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-green-600 flex justify-center items-center">
                        <i class="fas fa-users text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalPeminjaman }}
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm mt-8">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list text-gray-400"></i>
                    Daftar Pengembalian Alat
                </h2>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                    Total: {{ $pengembalians->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table id="pengembalian-table" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                No</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama
                                Peminjam</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal
                                Pengembalian</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Alat yang
                                Dikembalikan</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Penerima
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengembalians as $index => $pengembalian)
                            <tr class="hover:bg-gray-50/80 transition-colors border-b border-gray-100">
                                <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-semibold text-xs">
                                            <img src="{{ Avatar::create($pengembalian->peminjaman->peminjam->name_lengkap ?? ($pengembalian->peminjaman->peminjam->name ?? 'User'))->toBase64() }}"
                                                alt="{{ $pengembalian->peminjaman->peminjam->name }}"
                                                class="rounded-full w-8 h-8">
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $pengembalian->peminjaman->peminjam->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $pengembalian->peminjaman->peminjam->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian_sebenarnya)->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian_sebenarnya)->format('H:i') }}
                                        </span>
                                        @if ($pengembalian->tanggal_pengembalian_sebenarnya > $pengembalian->peminjaman->tanggal_pengembalian_rencana)
                                            <span class="text-xs text-red-600 font-medium mt-1">
                                                <i class="fas fa-exclamation-circle"></i> Terlambat
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        @foreach ($pengembalian->peminjaman->details->take(2) as $detail)
                                            <span class="text-sm text-gray-700">
                                                • {{ $detail->alat->nama_alat }} ({{ $detail->jumlah }}x)
                                            </span>
                                        @endforeach
                                        @if ($pengembalian->peminjaman->details->count() > 2)
                                            <span
                                                class="text-xs text-primary">+{{ $pengembalian->peminjaman->details->count() - 2 }}
                                                lainnya</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold text-xs">
                                            <img src="{{ Avatar::create($pengembalian->penerima->name_lengkap ?? ($pengembalian->penerima->name ?? 'Petugas'))->toBase64() }}"
                                                alt="{{ $pengembalian->penerima->name }}" class="rounded-full w-8 h-8">
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $pengembalian->penerima->name }}</div>
                                            <div class="text-xs text-gray-500">Petugas</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $kondisiConfig = [
                                            'baik' => [
                                                'bg' => 'bg-green-50',
                                                'text' => 'text-green-700',
                                                'border' => 'border-green-100',
                                                'icon' => 'fa-check-circle',
                                                'label' => 'Baik',
                                            ],
                                            'rusak_ringan' => [
                                                'bg' => 'bg-orange-50',
                                                'text' => 'text-orange-700',
                                                'border' => 'border-orange-100',
                                                'icon' => 'fa-exclamation-triangle',
                                                'label' => 'Rusak Ringan',
                                            ],
                                            'rusak_berat' => [
                                                'bg' => 'bg-red-50',
                                                'text' => 'text-red-700',
                                                'border' => 'border-red-100',
                                                'icon' => 'fa-times-circle',
                                                'label' => 'Rusak Berat',
                                            ],
                                            'hilang' => [
                                                'bg' => 'bg-gray-800',
                                                'text' => 'text-white',
                                                'border' => 'border-gray-800',
                                                'icon' => 'fa-ban',
                                                'label' => 'Hilang',
                                            ],
                                        ];
                                        $kondisi = $kondisiConfig[$pengembalian->kondisi] ?? $kondisiConfig['baik'];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-medium {{ $kondisi['bg'] }} {{ $kondisi['text'] }} border {{ $kondisi['border'] }}">
                                        <i class="fas {{ $kondisi['icon'] }}"></i>
                                        {{ $kondisi['label'] }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">Belum ada pengembalian</p>
                                        <p class="text-sm">Data pengembalian alat akan muncul di sini</p>
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
