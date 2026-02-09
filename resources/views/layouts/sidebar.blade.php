@php
    $role = auth()->user()->role->value; // Ambil value dari enum

    $allMenus = [
        'admin' => [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-table-cells-large'],
            ['label' => 'Manajemen Alat', 'route' => 'admin.alat.index', 'icon' => 'fas fa-briefcase'],
            ['label' => 'Manajemen Kategori', 'route' => 'admin.kategori.index', 'icon' => 'fas fa-tags'],
            [
                'label' => 'Akun Pengguna',
                'icon' => 'fas fa-users',
                'id' => 'menu-user',
                'subMenus' => [
                    [
                        'label' => 'Manajemen Akun Petugas',
                        'route' => 'admin.user-petugas.index',
                        'icon' => 'fas fa-user-tie',
                    ],
                    [
                        'label' => 'Manajemen Akun Peminjam',
                        'route' => 'admin.user-peminjam.index',
                        'icon' => 'fas fa-user',
                    ],
                ],
            ],
            ['label' => 'Data Peminjaman', 'route' => 'admin.peminjaman.index', 'icon' => 'fas fa-file-invoice'],
            ['label' => 'Data Pengembalian', 'route' => 'admin.pengembalian.index', 'icon' => 'fas fa-rotate-left'],
            ['label' => 'Log Aktifitas', 'route' => 'admin.log.index', 'icon' => 'fas fa-file-alt'],
        ],
        'petugas' => [
            ['label' => 'Dashboard', 'route' => 'petugas.dashboard', 'icon' => 'fas fa-table-cells-large'],
            ['label' => 'Daftar Alat', 'route' => 'petugas.alat.index', 'icon' => 'fas fa-briefcase'],
            [
                'label' => 'Menyetujui Peminjaman',
                'route' => 'petugas.approve-peminjaman.index',
                'icon' => 'fas fa-check-circle',
            ],
            [
                'label' => 'Menyetujui Pengembalian',
                'route' => 'petugas.approve-pengembalian.index',
                'icon' => 'fas fa-undo-alt',
            ],
        ],
        'peminjam' => [
            ['label' => 'Dashboard', 'route' => 'peminjam.dashboard', 'icon' => 'fas fa-table-cells-large'],
            ['label' => 'Daftar Alat', 'route' => 'peminjam.alat.index', 'icon' => 'fas fa-briefcase'],
            ['label' => 'Pengajuan Peminjaman', 'route' => 'peminjam.peminjaman.index', 'icon' => 'fas fa-plus-circle'],
            ['label' => 'Pengembalian', 'route' => 'peminjam.pengembalian.index', 'icon' => 'fas fa-undo-alt'],
        ],
    ];

    $menus = $allMenus[$role] ?? [];
@endphp

<div id="sidebar-overlay" class="fixed inset-0 bg-black/30 backdrop-blur-sm hidden z-40 lg:hidden"></div>
<div id="sidebar"
    class="fixed top-28 left-0 lg:left-4 bottom-4 z-50 w-72 bg-white/80 shadow-lg rounded-xl transition-all duration-700 ease-in-out flex flex-col -translate-x-full lg:translate-x-0">

    <div class="flex items-center justify-between py-3 px-6 bg-text/25 rounded-t-xl relative">
        <a href="#"
            class="sidebar-button inline-flex justify-center items-center bg-primary text-white font-bold text-base h-12 w-16 rounded-xl">
            CMS
        </a>
        <div class="" id="close-sidebar-2">
            <button type="button"
                class="sidebar-button w-10 h-10 text-darkChoco hover:bg-white/70 flex justify-center items-center rounded-full cursor-pointer group">
                <i class="fas fa-angles-left text-xl"></i>
            </button>
        </div>
    </div>

    <nav class="flex-1 p-6 space-y-3 overflow-y-auto custom-scrollbar">
        @foreach ($menus as $menu)
            @php
                $hasSubMenus = isset($menu['subMenus']);
                $isSubActive = false;
                if ($hasSubMenus) {
                    foreach ($menu['subMenus'] as $sub) {
                        if (request()->routeIs($sub['route'])) {
                            $isSubActive = true;
                            break;
                        }
                    }
                }
                $isActive = !$hasSubMenus && request()->routeIs($menu['route']);
            @endphp

            <div class="menu-item">
                @if (!$hasSubMenus)
                    {{-- Menu Standar --}}
                    <a href="{{ route($menu['route']) }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 group
                    {{ $isActive ? 'bg-linear-to-r from-primary to-secondary text-white shadow-lg scale-105' : 'text-text hover:bg-white/60 hover:shadow-md' }}">
                        <i
                            class="{{ $menu['icon'] }} {{ $isActive ? 'text-white' : 'text-text group-hover:scale-110' }} transition-all"></i>
                        <span class="font-medium pl-2">{{ $menu['label'] }}</span>
                    </a>
                @else
                    {{-- Menu dengan Submenu (Parent) --}}
                    <button type="button" onclick="toggleSubmenu('{{ $menu['id'] }}')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-300 group
                    {{ $isSubActive ? 'bg-text/10 text-primary font-bold' : 'text-text hover:bg-white/60' }}">
                        <div class="flex items-center space-x-3">
                            <i
                                class="{{ $menu['icon'] }} {{ $isSubActive ? 'text-primary' : 'text-text' }} transition-all"></i>
                            <span class="font-medium pl-2">{{ $menu['label'] }}</span>
                        </div>
                        <i id="icon-{{ $menu['id'] }}"
                            class="fas fa-chevron-down text-xs transition-transform duration-300 {{ $isSubActive ? 'rotate-180' : '' }}"></i>
                    </button>

                    {{-- Container Submenu --}}
                    <div id="{{ $menu['id'] }}"
                        class="{{ $isSubActive ? '' : 'hidden' }} pl-4 mt-2 space-y-2 border-l-2 border-primary/20 ml-6">
                        @foreach ($menu['subMenus'] as $sub)
                            @php $subItemActive = request()->routeIs($sub['route']); @endphp

                            <a href="{{ route($sub['route']) }}"
                                class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-all duration-300 group
                            {{ $subItemActive
                                ? 'bg-linear-to-r from-primary to-secondary text-white shadow-md'
                                : 'text-text/70 hover:text-primary hover:bg-white/50' }}">

                                {{-- Icon Submenu Sekarang Muncul Di Sini --}}
                                <i
                                    class="{{ $sub['icon'] }} text-sm {{ $subItemActive ? 'text-white' : 'group-hover:scale-110' }}"></i>

                                <span class="text-sm font-medium">
                                    {{ $sub['label'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        {{-- <div class="absolute bottom-4 w-full right-0"> --}}
        <hr class="border-gray-200 my-4">
        <form action="{{ route('logout') }}" method="post" class="logout-form">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 text-lg px-4 py-3 rounded-xl transition-all duration-300 group relative overflow-hidden bg-red-600 text-white hover:bg-red-600/80 shadow-lg hover:scale-105">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </button>
        </form>
    </nav>
</div>

<script>
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);

        // Toggle class 'hidden'
        submenu.classList.toggle('hidden');

        // Rotate icon
        if (submenu.classList.contains('hidden')) {
            icon.classList.remove('rotate-180');
        } else {
            icon.classList.add('rotate-180');
        }
    }
</script>

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
    const logoutForms = document.querySelectorAll('.logout-form');

    logoutForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin logout?',
                text: 'Sesi Anda akan berakhir!',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
