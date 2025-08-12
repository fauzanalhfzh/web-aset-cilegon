<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aset - Kelurahan Kedaleman Tahun {{ date('d-m-Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Noto Sans', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 10pt;
                margin: 0;
                padding: 0;
                width: auto; /* Remove fixed width */
                min-height: auto; /* Minimum height for A4, but allows growth */
            }

            .container {
                margin: 0.5in; /* Maintain A4 margins */
                padding: 0;
                width: 190mm; /* Fit content within A4 width */
                box-sizing: border-box;
            }

            .table-container {
                page-break-inside: avoid;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 4px;
                text-align: left;
            }

            th {
                background-color: #f3f4f6;
                font-weight: bold;
            }

            .signature-img {
                max-height: 40px;
                width: auto;
            }

            @page {
                size: A5; /* Set page size to A4 */
                margin: 0.5in; /* Consistent margins for printing */
            }
        }
    </style>
</head>

<body class="bg-white">
    <!-- Print Button -->
    <div class="no-print p-4 bg-gray-100 border-b">
        <button onclick="window.print()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            🖨️ Print Laporan
        </button>
    </div>

    <!-- Main Content -->
    <div class="container max-w-[190mm] mx-auto p-4">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="text-lg font-bold">WEB SISTEM</h1>
            <h2 class="text-lg font-bold">KELURAHAN KEDALEMAN KOTA CILEGON</h2>
            <p class="text-xs">Alamat: Jl. Teuku Umar Kalang Anyar Kode Pos 42422</p>
            <p class="text-xs">Kelurahan Kedaleman, Kecamatan Cibeber, Kota Cilegon</p>
            <hr class="border-t-2 border-gray-800 my-2">
            <h3 class="text-xl font-bold">LAPORAN ASET</h3>
            <p class="text-xs">Dicetak pada: <span id="print-date"></span></p>
        </div>

        <!-- Table -->
        <div class="table-container mb-6">
            <table class="w-full text-xs border border-gray-800">
                <caption class="text-sm font-bold mb-2">Data Aset Kelurahan Kedaleman</caption>
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-2 py-1">No</th>
                        <th class="border px-2 py-1">Nama Aset</th>
                        <th class="border px-2 py-1">Kategori</th>
                        <th class="border px-2 py-1">Jmlh</th>
                        <th class="border px-2 py-1">Kondisi</th>
                        <th class="border px-2 py-1">Tgl. Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp
                    @foreach ($asets as $aset)
                     <tr>
                        <td class="border px-2 py-1">{{ $no++ }}</td>
                        <td class="border px-2 py-1">{{ $aset->nama_aset }}</td>
                        <td class="border px-2 py-1">{{ $aset->kategori->nama_kategori }}</td>
                        <td class="border px-2 py-1">{{ $aset->jumlah }}</td>
                        <td class="border px-2 py-1">{{ $aset->kondisi }}</td>
                        <td class="border px-2 py-1">  {{ \Carbon\Carbon::parse($aset->tanggal_pembelian)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                   
                </tbody>
            </table>
        </div>

       <!-- Signatures -->
<div class="flex justify-between mt-4 text-xs">
    <!-- Kuasa Penanggung Jawab -->
    <div class="text-center w-1/2">
        <p>KUASA PENANGGUNG JAWAB</p>
        <img class="signature-img mx-auto my-2" style="display: none;" alt="Tanda Tangan Lurah">
        <div class="h-24"></div> <!-- Spasi untuk tanda tangan -->
        <p class="underline">(FAISAL TANJUNG S.Kom. M)</p>
    </div>

    <!-- Pembantu Pengurus Barang Pengguna -->
    <div class="text-center w-1/2">
        <p class="mx-2">{{ "Cilegon, " . date('d-m-Y') }}</p>
        <p>PEMBANTU PENGURUS</p>
        <p>BARANG PENGGUNA</p>

        @if ($admin && $admin->sign)
            <img class="signature-img mx-auto my-2" src="{{ Storage::url($admin->sign) }}" style="width: 80px;" alt="Tanda Tangan Admin">
            <div class="h-16"></div> <!-- Spasi bawah jika tanda tangan ada -->
            <p class="underline">({{ $admin->name }})</p>
        @else
            <img class="signature-img mx-auto my-2" style="display: none;" alt="Tanda Tangan Admin">
            <div class="h-16"></div> <!-- Spasi bawah jika tanda tangan kosong -->
            <p class="underline">(ALI IMRON, ST)</p>
        @endif
    </div>
</div>

    </div>

    <script>
        // Set print date
        document.getElementById('print-date').textContent = new Date().toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        });
    </script>
</body>

</html>