<x-app-layout title="Manajemen Alat">
    <div class="pt-3">
        <div class="flex flex-wrap items-center justify-between mb-4">
            <div class="space-y-2">
                <h1 class="text-2xl text-heading font-bold">Daftar Alat</h1>
                <p class="text-text font-lato">Lihat Daftar Alat di Sini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Total Alat
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-primary flex justify-center items-center">
                        <i class="fas fa-briefcase text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $totalAlat }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Jumlah Alat Tersedia
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-green-600 flex justify-center items-center">
                        <i class="fas fa-circle-check text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $alatTersedia }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Jumlah Stock Alat Menipis
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-orange-600 flex justify-center items-center">
                        <i class="fas fa-exclamation-triangle text-white text-base"></i>
                    </div>

                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $alatMenipis }}
                </div>
            </div>
            <div class="bg-white shadow-md p-4 rounded-xl geometric-shape hover:shadow-lg">
                <div class="flex flex-row justify-between items-center space-y-0 pb-2">
                    <h1 class="text-sm font-medium text-text">
                        Jumlah Alat Tidak Tersedia
                    </h1>
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex justify-center items-center">
                        <i class="fas fa-circle-xmark text-white text-base"></i>
                    </div>
                </div>
                <div class="text-2xl text-primary mt-1 font-bold">
                    {{ $alatTidakTersedia }}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
            @foreach ($alats as $alat)
                <div
                    class="bg-white rounded-2xl shadow-lg p-5 geometric-shape hover:shadow-xl relative overflow-hidden">

                    <div class="absolute top-4 right-4 z-10">
                        @if ($alat->stok > 10)
                            <span
                                class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-200 shadow-sm">
                                Tersedia
                            </span>
                        @elseif($alat->stok > 0)
                            <span
                                class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full border border-orange-200 shadow-sm">
                                Stok Menipis
                            </span>
                        @else
                            <span
                                class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-200 shadow-sm">
                                Habis
                            </span>
                        @endif
                    </div>

                    @if ($alat->foto_alat)
                        <div class="w-full h-48 overflow-hidden rounded-lg mb-4">
                            <img src="{{ Storage::url($alat->foto_alat) }}" alt="{{ $alat->nama_alat }}"
                                class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif

                    <div class="flex flex-col">
                        <h1 class="text-heading font-bold text-xl mb-2 truncate">{{ $alat->nama_alat }}</h1>

                        <div class="flex flex-col gap-1">
                            <span class="text-text text-sm opacity-75">
                                <i class="fas fa-tag mr-2 w-4"></i>
                                {{ $alat->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                            </span>

                            <span class="text-text text-sm font-medium">
                                <i class="fas fa-boxes mr-2 w-4"></i>
                                Stok: <span
                                    class="{{ $alat->stok > 10 ? 'text-green-600' : ($alat->stok > 0 ? 'text-orange-600' : 'text-red-600') }} font-bold">
                                    {{ $alat->stok }} Unit
                                </span>
                            </span>
                        </div>
                    </div>
            @endforeach
        </div>

        <div class="flex justify-end mt-4">
            {{ $alats->links() }}
        </div>
    </div>
</x-app-layout>
