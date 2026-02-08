@php
    $role = auth()->user()->role; // Sesuaikan dengan kolom role di database-mu

    $allMenus = [
        'admin' => [
            ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'fas fa-table-cells-large'],
            ['label' => 'Jobs', 'route' => 'jobs.index', 'icon' => 'fas fa-briefcase'],
            ['label' => 'CV', 'route' => 'cv.index', 'icon' => 'fas fa-file'],
            ['label' => 'Partner', 'route' => 'partner.index', 'icon' => 'fas fa-handshake'],
            ['label' => 'Partner Request', 'route' => 'partnerReq.index', 'icon' => 'fas fa-user-plus'],
            ['label' => 'Employee', 'route' => 'employee.index', 'icon' => 'fas fa-user-tie'],
            ['label' => 'Account', 'route' => 'account.index', 'icon' => 'fas fa-user-circle'],
        ],
        'petugas' => [
            ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'fas fa-table-cells-large'],
            ['label' => 'Jobs', 'route' => 'jobs.index', 'icon' => 'fas fa-briefcase'],
            ['label' => 'Blog', 'route' => 'blogs.index', 'icon' => 'fas fa-newspaper'],
        ],
        'peminjam' => [
            ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'fas fa-table-cells-large'],
            ['label' => 'Portfolio', 'route' => 'portfolios.index', 'icon' => 'fas fa-layer-group'],
            ['label' => 'Faq', 'route' => 'faqs.index', 'icon' => 'fas fa-question-circle'],
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
                // Logic Active: Cek apakah route saat ini sama dengan route menu
                $isActive = request()->routeIs($menu['route']);
            @endphp

            <a href="{{ route($menu['route']) }}" title="{{ $menu['label'] }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 group relative overflow-hidden 
            {{ $isActive
                ? 'bg-linear-to-r from-primary to-secondary text-white shadow-lg scale-105'
                : 'text-text hover:bg-white/60 hover:shadow-md hover:scale-102 backdrop-blur-md' }}">

                <i
                    class="{{ $menu['icon'] }} 
                {{ $isActive ? 'text-white' : 'text-text group-hover:scale-110' }} 
                transition-all duration-300"></i>

                <span class="font-medium pl-2">
                    {{ $menu['label'] }}
                </span>
            </a>
        @endforeach

        <hr class="border-gray-200 my-4">
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <a href="javascript:void(0)" id="logout-btn"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-300 group relative overflow-hidden bg-red-600 text-white hover:bg-red-600/80 shadow-lg hover:scale-105"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-arrow-right-from-bracket"></i>
                <span class="font-medium pl-2">Logout</span>
            </a>
        </form>
    </nav>
</div>
