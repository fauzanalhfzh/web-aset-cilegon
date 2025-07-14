<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Tambah Aset Keluar</h1>
        <form action="{{ route('aset-keluar.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="aset_id" class="block text-gray-700 font-medium mb-2">Nama Aset</label>
                <select name="aset_id" id="aset_id" class="w-full border rounded-lg p-2">
                    @foreach ($asets as $aset)
                        <option value="{{ $aset->id }}">{{ $aset->nama_aset }} (Tersedia: {{ $aset->jumlah }})</option>
                    @endforeach
                </select>
                @error('aset_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="jumlah" class="block text-gray-700 font-medium mb-2">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="w-full border rounded-lg p-2" value="{{ old('jumlah') }}" min="1">
                @error('jumlah')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="tanggal_keluar" class="block text-gray-700 font-medium mb-2">Tanggal Keluar</label>
                <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="w-full border rounded-lg p-2" value="{{ old('tanggal_keluar') }}">
                @error('tanggal_keluar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="keterangan" class="block text-gray-700 font-medium mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" class="w-full border rounded-lg p-2">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                <a href="{{ route('aset-keluar.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>