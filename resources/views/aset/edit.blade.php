<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Edit Aset</h1>
            <a href="{{ route('aset.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        </div>
        <form id="editAsetForm" action="{{ route('aset.update', $aset) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700">Nama Aset</label>
                <input type="text" name="nama_aset" value="{{ $aset->nama_aset }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Kategori</label>
                <select name="kategori_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ $aset->kategori_id == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Jumlah</label>
                <input type="number" name="jumlah" value="{{ $aset->jumlah }}" class="w-full border rounded px-3 py-2"
                    min="1" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Kondisi</label>
                <select name="kondisi" class="w-full border rounded px-3 py-2" required>
                    <option value="Baik" {{ $aset->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak" {{ $aset->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="Perlu Perbaikan" {{ $aset->kondisi == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu
                        Perbaikan</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" value="{{ $aset->tanggal_pembelian }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Perbarui</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#editAsetForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;

                Swal.fire({
                    title: 'Perbarui Aset',
                    text: "Apakah Anda yakin ingin memperbarui aset ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Perbarui!',
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
                                    window.location.href = '{{ route('aset.index') }}';
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>