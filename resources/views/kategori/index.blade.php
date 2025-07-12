<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Daftar Kategori Aset</h1>
            <div class="space-x-2">
                <a href="{{ route('kategori.create') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah Kategori</a>
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
            </div>
        </div>
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Nama Kategori</th>
                    <th class="px-4 py-2">Jumlah Aset</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoris as $kategori)
                    <tr>
                        <td class="border px-4 py-2">{{ $kategori->nama_kategori }}</td>
                        <td class="border px-4 py-2">{{ $kategori->asets()->count() }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('kategori.edit', $kategori) }}"
                                class="text-blue-500 hover:underline">Edit</a>
                            <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
