<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Pengembalian</h1>
        <form action="{{ route('pengembalian.update', $pengembalian) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="aset_keluar_id" class="block text-gray-700 font-medium mb-2">Aset Keluar</label>
                <select name="aset_keluar_id" id="aset_keluar_id" class="w-full border rounded-lg p-2">
                    @foreach ($asetKeluars as $asetKeluar)
                        <option value="{{ $asetKeluar->id }}" {{ $pengembalian->aset_keluar_id == $asetKeluar->id ? 'selected' : '' }}>
                            {{ $asetKeluar->aset->nama_aset }} (Keluar: {{ $asetKeluar->jumlah }})
                        </option>
                    @endforeach
                </select>
                @error('aset_keluar_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="jumlah" class="block text-gray-700 font-medium mb-2">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="w-full border rounded-lg p-2" value="{{ old('jumlah', $pengembalian->jumlah) }}" min="1">
                @error('jumlah')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @error
            </div>
            <div class="mb-4">
                <label for="tanggal_pengembalian" class="block text-gray-700 font-medium mb-2">Tanggal Pengembalian</label>
                <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" class="w-full border rounded-lg p-2" value="{{ old('tanggal_pengembalian', $pengembalian->tanggal_pengembalian) }}">
                @error('tanggal_pengembalian')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="keterangan" class="block text-gray-700 font-medium mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="w-full border rounded-lg p-2">{{ old('keterangan', $pengembalian->keterangan) }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                <a href="{{ route('pengembalian.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>