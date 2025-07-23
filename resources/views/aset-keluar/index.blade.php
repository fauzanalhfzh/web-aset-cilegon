<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Aset Keluar</h1>
            <div class="space-x-2">
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('aset-keluar.create') }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah Aset Keluar</a>
                @endif
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
            </div>
        </div>
        <table id="asetKeluarTable" class="w-full table-auto">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Nama Aset</th>
                    <th class="px-4 py-2">Jumlah</th>
                    <th class="px-4 py-2">Tanggal Keluar</th>
                    <th class="px-4 py-2">Keterangan</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Disetujui Oleh</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($asetKeluars as $asetKeluar)
                    <tr>
                        <td class="border px-4 py-2">{{ $asetKeluar->aset->nama_aset }}</td>
                        <td class="border px-4 py-2">{{ $asetKeluar->jumlah }}</td>
                        <td class="border px-4 py-2">
                            {{ \Carbon\Carbon::parse($asetKeluar->tanggal_keluar)->format('d-m-Y') }}
                        </td>
                        <td class="border px-4 py-2">{{ $asetKeluar->keterangan ?? '-' }}</td>
                        <td class="border px-4 py-2">
                            {{ $asetKeluar->status === 'approved' ? 'Disetujui' : 'Menunggu Persetujuan' }}
                        </td>
                        <td class="border px-4 py-2">{{ $asetKeluar->approver ? $asetKeluar->approver->name : '-' }}
                        </td>
                        <td class="border px-4 py-2 space-x-2">
                            @if (auth()->user()->role === 'admin' && $asetKeluar->status === 'pending')
                                <a href="{{ route('aset-keluar.edit', $asetKeluar) }}"
                                    class="bg-blue-500 text-white p-2 my-1 rounded-lg">Edit</a>
                                <form action="{{ route('aset-keluar.destroy', $asetKeluar) }}" method="POST"
                                    class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white p-2 my-1 rounded-lg">Hapus</button>
                                </form>
                            @endif
                            @if (auth()->user()->role === 'lurah' && $asetKeluar->status === 'pending')
                                <a href="{{ route('aset-keluar.approve', $asetKeluar) }}"
                                    class="bg-green-500 text-white p-2 my-1 rounded-lg approve-link">Setujui</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#asetKeluarTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' // Indonesian translation
                },
                pageLength: 10,
                responsive: true,
                columnDefs: [{
                        orderable: false,
                        targets: -1
                    } // Disable sorting on action column
                ]
            });

            // SweetAlert2 for Delete Confirmation
            $('.delete-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data aset keluar ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: $(form).serialize(),
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // SweetAlert2 for Approve Confirmation
            $('.approve-link').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                Swal.fire({
                    title: 'Setujui Aset Keluar',
                    text: "Apakah Anda yakin ingin menyetujui aset keluar ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.message ||
                                        'Terjadi kesalahan.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            // SweetAlert2 for Create/Edit Success (from session flash)
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif
            @if (session('error'))
                Swal.fire({
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</x-app-layout>
