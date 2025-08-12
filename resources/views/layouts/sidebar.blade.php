<div id="sidebar" class="sidebar-transition w-64 bg-white shadow-lg flex flex-col h-screen overflow-y-auto">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center">
                <img src="{{ asset('build/assets/logo1.png') }}" alt="">
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-xs text-gray-500">Kel. Kedaleman Kota Cilegon</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        @if (auth()->user()->role === 'admin')
            <a href="{{ route('aset.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('aset.create') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Aset</span>
            </a>
            <a href="{{ route('kategori.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('kategori.index') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Kategori Aset</span>
            </a>
        @endif

        <a href="{{ route('aset.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('aset.index') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
            <i class="fas fa-list"></i>
            <span>Daftar Aset</span>
        </a>
        <a href="{{ route('aset-keluar.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('aset-keluar.index') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
            <i class="fas fa-sign-out-alt"></i>
            <span>Aset Keluar</span>
        </a>
        <!-- <a href="{{ route('pengembalian.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('pengembalian.index') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
            <i class="fas fa-undo"></i>
            <span>Pengembalian</span>
        </a> -->
        <a href="{{ route('laporan.aset.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('laporan.aset.index') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>

        @if (auth()->user()->role === 'admin')
            <a href="{{ url('users') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
                <i class="fas fa-user"></i>
                <span>Kelola Users</span>
            </a>
        @endif

        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50 transition-colors' }}">
            <i class="fas fa-user"></i>
            <span>My Profile</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3 mb-3">
            @if (auth()->user()->foto)
                <img src="{{ Storage::url(auth()->user()->foto) }}" alt="User" class="w-10 h-10 rounded-full">
            @else
                <img src="{{ asset('build/assets/user.png') }}" alt="User" class="w-10 h-10 rounded-full">
            @endif
            <div>
                <p class="font-medium text-gray-700">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center space-x-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>