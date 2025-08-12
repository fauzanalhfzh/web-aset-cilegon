<x-app-layout>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Aset</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalAset }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up"></i> +12% dari bulan lalu
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Aset Aktif</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $asetAktif }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up"></i> +8% dari bulan lalu
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Perlu Maintenance</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $perluMaintenance }}</p>
                    <p class="text-red-600 text-sm mt-1">
                        <i class="fas fa-exclamation-triangle"></i> Perlu perhatian
                    </p>
                </div>
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i class="fas fa-tools text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- <div class="bg-white p-6 rounded-xl shadow-sm card-hover animate-fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Nilai Total</p>
                    <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($nilaiTotal, 0, ',', '.') }}</p>
                    <p class="text-blue-600 text-sm mt-1">
                        <i class="fas fa-info-circle"></i> Nilai inventaris
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                </div>
            </div>
        </div> -->
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Asset Distribution Chart -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Aset</h3>
            <div class="h-64">
                <canvas id="assetDistributionChart"></canvas>
            </div>
        </div>

        <!-- Monthly Trends -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Bulanan</h3>
            <div Никсель х-64">
                <canvas id="monthlyTrendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Assets Table -->
    <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Aset Terbaru</h3>
                <a href="{{ route('aset.index') }}" class="text-blue-600 hover:text-blue-800">Lihat Semua</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Aset</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th> -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th> -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($recentAsets as $aset)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $aset->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $aset->nama_aset }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $aset->kategori->nama_kategori }}</td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $aset->status == 'Aktif' ? 'bg-green-100 text-green-800' : ($aset->status == 'Maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $aset->status }}
                                </span>
                            </td> -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($aset->tanggal_pembelian)->format('d M Y') }}</td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($aset->jumlah, 0, ',', '.') }}</td> -->
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm card-hover">
            <div class="text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-plus text-blue-600 text-xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Tambah Aset Baru</h4>
                <p class="text-gray-600 text-sm mb-4">Daftarkan aset baru ke dalam sistem inventaris</p>
                <a href="{{ route('aset.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Mulai Sekarang
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm card-hover">
            <div class="text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-excel text-green-600 text-xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Export Data</h4>
                <p class="text-gray-600 text-sm mb-4">Unduh laporan inventaris dalam format Excel</p>
                <a href="{{ route('laporan.aset.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                    Download
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm card-hover">
            <div class="text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Analisis Aset</h4>
                <p class="text-gray-600 text-sm mb-4">Lihat analisis mendalam tentang aset perusahaan</p>
                <a href="{{ route('laporan.aset.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    Lihat Analisis
                </a>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Asset Distribution Chart
        const ctx1 = document.getElementById('assetDistributionChart').getContext('2d');
        new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: @json($kategoriData->pluck('nama_kategori')),
                datasets: [{
                    data: @json($kategoriData->pluck('jumlah')),
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Monthly Trends Chart
        const ctx2 = document.getElementById('monthlyTrendsChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: @json($monthlyTrends->pluck('month')),
                datasets: [{
                    label: 'Jumlah Aset',
                    data: @json($monthlyTrends->pluck('count')),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>