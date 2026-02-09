<x-app-layout title="Manajemen User Petugas">
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

            #petugas-table thead th {
                @apply bg-linear-to-r from-primary to-secondary text-white font-semibold py-4 px-4 text-left;
            }

            #petugas-table tbody tr {
                @apply hover:bg-gray-50 transition-colors;
            }

            #petugas-table tbody td {
                @apply py-4 px-4 border-b border-gray-200;
            }
        </style>
    @endpush

    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Manajemen Pengguna Petugas</h1>
                <p class="text-text font-lato">Kelola akun petugas di sini</p>
            </div>
            <div>
                <button type="button" id="open-modal"
                    class="flex items-center gap-3 text-white font-medium px-6 py-3 rounded-lg bg-linear-to-r from-primary to-secondary cursor-pointer hover:from-secondary hover:to-primary shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-plus"></i>
                    Tambah Petugas Baru
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div
                class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-shadow duration-300 border-l-4 border-primary">
                <div class="flex flex-row justify-between items-center">
                    <div>
                        <h1 class="text-sm font-medium text-gray-600 mb-2">
                            Total Akun Petugas
                        </h1>
                        <div class="text-3xl text-primary font-bold">
                            {{ $totalPetugas }}
                        </div>
                    </div>
                    <div
                        class="w-16 h-16 rounded-full bg-linear-to-br from-primary to-secondary flex justify-center items-center shadow-lg">
                        <i class="fas fa-user-tie text-white text-2xl"></i>
                    </div>
                </div>
            </div>
            <div
                class="bg-white shadow-lg p-6 rounded-xl hover:shadow-2xl transition-shadow duration-300 border-l-4 border-green-500">
                <div class="flex flex-row justify-between items-center">
                    <div>
                        <h1 class="text-sm font-medium text-gray-600 mb-2">
                            Petugas Aktif
                        </h1>
                        <div class="text-3xl text-green-600 font-bold">
                            {{ $totalPetugasActive }}
                        </div>
                    </div>
                    <div
                        class="w-16 h-16 rounded-full bg-linear-to-br from-green-400 to-green-600 flex justify-center items-center shadow-lg">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable Section -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-users text-gray-400"></i>
                    Daftar Petugas
                </h2>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                    Total: {{ count($userPetugas) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table id="petugas-table" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                No</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Petugas
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Username
                            </th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Status</th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($userPetugas as $index => $petugas)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-xs font-bold border border-indigo-100">
                                            {{ strtoupper(substr($petugas->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $petugas->nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm text-gray-600 bg-gray-50 border border-gray-200 px-2 py-0.5 rounded">
                                        {{ $petugas->username }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-700">{{ $petugas->email }}</span>
                                        <span class="text-xs text-gray-400">{{ $petugas->no_telp ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if (!$petugas->status_blokir)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-red-50 text-red-700 border border-red-100">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-3">
                                        @if ($petugas->status_akun === 0)
                                            <form action="{{ route('admin.user-petugas.toggleStatus', $petugas->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-green-600 transition-colors"
                                                    title="Aktifkan">
                                                    <i class="fas fa-user-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.user-petugas.toggleStatus', $petugas->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-gray-400 hover:text-green-600 transition-colors"
                                                    title="Non-Aktifkan">
                                                    <i class="fas fa-user-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button"
                                            class="edit-petugas text-gray-400 hover:text-blue-600 transition-colors"
                                            data-id="{{ $petugas->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.user-petugas.destroy', $petugas->id) }}"
                                            method="POST" class="delete-form inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-gray-400 hover:text-red-600 transition-colors"
                                                title="Hapus">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>

<div id="create-new-petugas" class="fixed inset-0 z-99999 hidden items-center justify-center p-4 animate-fade-in">
    <div class="close-modal absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="bg-white max-w-lg w-full rounded-xl shadow-2xl relative border border-white/20 overflow-hidden">
        <div class="bg-linear-to-r from-heading via-primary to-secondary p-8 text-center overflow-hidden relative">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative z-10">
                <div
                    class="w-16 h-16 bg-white/20 rounded-full flex justify-center items-center text-white mx-auto mb-4 backdrop-blur-sm">
                    <i class="fas fa-user text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Tambah Petugas Baru</h1>
                <p class="text-white/90 text-base font-lato">Akun akan otomatis aktif dengan password default</p>
            </div>

            <button
                class="close-modal absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white cursor-pointer transition-all duration-300 hover:rotate-90">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-8 max-h-96 overflow-y-auto custom-scrollbar">
            <form action="{{ route('admin.user-petugas.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="group">
                    <label for="nama_petugas"
                        class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                        <i class="fas fa-user-tie"></i>
                        Nama Lengkap Petugas <span class="text-red-400">*</span>
                    </label>
                    <input type="text" id="nama_petugas" name="nama_petugas" required
                        class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                        placeholder="Contoh: Budi Setiawan">
                </div>

                <div class="group">
                    <label for="email"
                        class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                        <i class="fas fa-envelope"></i>
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email"
                        class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                        placeholder="petugas@email.com">
                </div>

                <div class="group">
                    <label for="no_telp"
                        class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                        <i class="fas fa-phone"></i>
                        Nomor Telepon
                    </label>
                    <input type="text" id="no_telp" name="no_telp"
                        class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                        placeholder="0812xxxxxxxx">
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="button"
                        class="close-modal flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                    <button type="submit"
                        class="px-4 flex-1 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/30 cursor-pointer transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($userPetugas as $petugas)
    <div id="edit-petugas-{{ $petugas->id }}"
        class="fixed inset-0 z-99999 hidden items-center justify-center p-4 animate-fade-in">
        <div class="close-modal absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="bg-white max-w-lg w-full rounded-xl shadow-2xl relative border border-white/20 overflow-hidden">
            <div class="bg-linear-to-r from-heading via-primary to-secondary p-8 text-center overflow-hidden relative">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="relative z-10">
                    <div
                        class="w-16 h-16 bg-white/20 rounded-full flex justify-center items-center text-white mx-auto mb-4 backdrop-blur-sm">
                        <i class="fas fa-user text-3xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white mb-2">Edit Petugas</h1>
                    <p class="text-white/90 text-base font-lato">Perbaiki informasi petugas jika diperlukan</p>
                </div>

                <button
                    class="close-modal absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center text-white cursor-pointer transition-all duration-300 hover:rotate-90">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <div class="p-8 max-h-96 overflow-y-auto custom-scrollbar">
                <form action="{{ route('admin.user-petugas.update', $petugas->id) }}" method="POST"
                    class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="group">
                        <label for="edit_nama_petugas"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                            <i class="fas fa-user-tie"></i>
                            Nama Lengkap Petugas <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="edit_nama_petugas" name="edit_nama_petugas" required
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                            placeholder="Contoh: Budi Setiawan" value="{{ $petugas->nama_lengkap }}">
                    </div>

                    <div class="group">
                        <label for="edit_email"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                            <i class="fas fa-envelope"></i>
                            Alamat Email
                        </label>
                        <input type="email" id="edit_email" name="edit_email"
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                            placeholder="petugas@email.com" value="{{ $petugas->email }}">
                    </div>

                    <div class="group">
                        <label for="edit_no_telp"
                            class="flex items-center gap-2 text-sm font-medium text-darkChoco mb-2 group-hover:text-heading transition-colors">
                            <i class="fas fa-phone"></i>
                            Nomor Telepon
                        </label>
                        <input type="text" id="edit_no_telp" name="edit_no_telp"
                            class="w-full px-4 py-3 bg-slate-50 border border-text/25 rounded-lg focus:ring-2 focus:ring-secondary focus:border-transparent transition-all duration-200"
                            placeholder="0812xxxxxxxx" value="{{ $petugas->no_telp }}">
                    </div>

                    <div class="flex items-center gap-3 pt-4">
                        <button type="button"
                            class="close-modal flex-1 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 cursor-pointer">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                        <button type="submit"
                            class="px-4 flex-1 py-2 rounded-lg bg-primary text-white hover:bg-primary/90 shadow-lg shadow-primary/30 cursor-pointer transition-all">
                            <i class="fas fa-save mr-2"></i> Simpan Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach


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
            icon: 'warning',
            title: 'Gagal!',
            text: '{{ session('error') }}',
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
                title: 'Yakin ingin menghapus',
                text: 'Data yang sudah dihapus tidak dapat dipulihkan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<script src="{{ asset('asset-admin/js/user-petugas/index.js') }}"></script>
