<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Tambah Aset</h1>
            <a href="{{ route('aset.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        </div>
        <form action="{{ route('aset.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Nama Aset</label>
                <input type="text" name="nama_aset" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Kategori</label>
                <select name="kategori_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Jumlah</label>
                <input type="number" name="jumlah" class="w-full border rounded px-3 py-2" min="1" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Kondisi</label>
                <select name="kondisi" class="w-full border rounded px-3 py-2" required>
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                    <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
        </form>
    </div>
</x-app-layout>