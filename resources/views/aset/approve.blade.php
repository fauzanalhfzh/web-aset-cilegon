<x-app-layout>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Setujui Aset</h1>
            <a href="{{ route('aset.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
        </div>
        <p class="mb-4">Apakah Anda yakin ingin menyetujui aset <strong>{{ $aset->nama_aset }}</strong>?</p>
        <form action="{{ route('aset.approve', $aset) }}" method="POST">
            @csrf
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Setujui</button>
            <a href="{{ route('aset.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
        </form>
    </div>
</x-app-layout>