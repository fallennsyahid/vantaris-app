<x-app-layout title="Log Aktifitas">
    @push('styles')
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <style>
            /* Custom DataTables Styling */
            .dataTables_wrapper {
                padding: 0;
            }

            .dataTables_filter input {
                @apply border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent;
            }

            .dataTables_length select {
                @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                @apply px-3 py-2 mx-1 border border-gray-300 rounded-lg hover:bg-primary hover:text-white hover:border-primary transition-colors;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                @apply bg-primary text-white border-primary;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
                @apply opacity-50 cursor-not-allowed;
            }

            #log-table thead th {
                @apply bg-linear-to-r from-primary to-secondary text-white font-semibold py-4 px-4 text-left;
            }

            #log-table tbody tr {
                @apply hover:bg-gray-50 transition-colors;
            }

            #log-table tbody td {
                @apply py-4 px-4 border-b border-gray-200;
            }
        </style>
    @endpush

    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Log Aktivitas Sistem</h1>
                <p class="text-text font-lato">Riwayat semua aktivitas yang terjadi di sistem</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div
                class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-shadow duration-300 border-l-4 border-primary">
                <div class="flex flex-row justify-between items-center">
                    <div>
                        <h1 class="text-sm font-medium text-gray-600 mb-2">
                            Total Log Aktivitas
                        </h1>
                        <div class="text-3xl text-primary font-bold">
                            {{ $totalLogs }}
                        </div>
                    </div>
                    <div
                        class="w-16 h-16 rounded-full bg-linear-to-br from-primary to-secondary flex justify-center items-center shadow-lg">
                        <i class="fas fa-history text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            <div
                class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex flex-row justify-between items-center">
                    <div>
                        <h1 class="text-sm font-medium text-gray-600 mb-2">
                            Aktivitas Hari Ini
                        </h1>
                        <div class="text-3xl text-green-600 font-bold">
                            {{ $totalHariIni }}
                        </div>
                    </div>
                    <div
                        class="w-16 h-16 rounded-full bg-linear-to-br from-green-400 to-green-600 flex justify-center items-center shadow-lg">
                        <i class="fas fa-calendar-day text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable Section -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list text-gray-400"></i>
                    Daftar Log Aktivitas
                </h2>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                    Total: {{ count($logs) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Waktu</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Pengguna</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Entitas</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($logs as $index => $log)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $log->created_at->format('d M Y') }}</span>
                                        <span
                                            class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-linear-to-br from-primary to-secondary flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($log->user->nama_lengkap ?? 'System', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $log->user->nama_lengkap ?? 'System' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $log->user->username ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $aksiColors = [
                                            'create' => 'bg-green-100 text-green-800',
                                            'update' => 'bg-blue-100 text-blue-800',
                                            'delete' => 'bg-red-100 text-red-800',
                                            'restore' => 'bg-purple-100 text-purple-800',
                                            'force_delete' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $aksiIcons = [
                                            'create' => 'fa-plus-circle',
                                            'update' => 'fa-edit',
                                            'delete' => 'fa-trash',
                                            'restore' => 'fa-undo',
                                            'force_delete' => 'fa-times-circle',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium {{ $aksiColors[$log->aksi] ?? 'bg-gray-100 text-gray-800' }}">
                                        <i class="fas {{ $aksiIcons[$log->aksi] ?? 'fa-circle' }}"></i>
                                        {{ ucfirst($log->aksi) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-tag"></i>
                                        {{ ucfirst($log->entitas) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @php
                                        $detail = json_decode($log->keterangan_dan_detail, true);
                                        $message = $detail['message'] ?? 'Tidak ada keterangan';
                                    @endphp
                                    <div class="max-w-md">
                                        <p class="line-clamp-2">{{ $message }}</p>
                                        @if (strlen($message) > 100)
                                            <button type="button" onclick="showDetail({{ json_encode($detail) }})"
                                                class="text-primary text-xs mt-1 hover:underline">
                                                Lihat detail
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                $('#log-table').DataTable({
                    order: [
                        [1, 'desc']
                    ], // Sort by waktu descending
                    pageLength: 25,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ log",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 log",
                        infoFiltered: "(difilter dari _MAX_ total log)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        },
                        emptyTable: "Tidak ada log aktivitas",
                        zeroRecords: "Tidak ada log yang cocok dengan pencarian"
                    }
                });
            });

            function showDetail(detail) {
                let htmlContent = '<div class="text-left space-y-2">';

                // Show message
                if (detail.message) {
                    htmlContent += `<div class="mb-3">
                        <p class="text-gray-700">${detail.message}</p>
                    </div>`;
                }

                // Show changes if available
                if (detail.changes && Object.keys(detail.changes).length > 0) {
                    htmlContent += '<div class="border-t pt-3"><h4 class="font-semibold text-gray-800 mb-2">Perubahan:</h4>';
                    for (const [key, value] of Object.entries(detail.changes)) {
                        const originalValue = detail.original && detail.original[key] ? detail.original[key] : '-';
                        htmlContent +=
                            `<div class="mb-2"><span class="font-medium text-gray-700">${key}:</span> <span class="text-red-600">${originalValue}</span> <i class="fas fa-arrow-right text-gray-400 text-xs"></i> <span class="text-green-600">${value}</span></div>`;
                    }
                    htmlContent += '</div>';
                }

                htmlContent += '</div>';

                Swal.fire({
                    title: 'Detail Log Aktivitas',
                    html: htmlContent,
                    icon: 'info',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#3b82f6',
                    width: '600px'
                });
            }
        </script>
    @endpush
</x-app-layout>
