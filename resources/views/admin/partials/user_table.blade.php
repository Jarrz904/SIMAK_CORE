<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu & Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama & Wilayah</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lampiran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            {{-- Loop menggunakan data dari Alpine.js (pagedData) --}}
            <template x-for="item in pagedData" :key="item.id + item.kat_row">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900" x-text="item.date_human"></div>
                        <div class="text-xs text-gray-500" x-text="item.time + ' WIB'"></div>
                        <span class="mt-1 inline-flex px-2 text-xs font-bold leading-5 rounded-full bg-blue-100 text-blue-800" x-text="item.display_kat"></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900" x-text="item.name"></div>
                        <div class="text-xs text-gray-500 italic" x-text="item.location"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-2">
                            <template x-for="(foto, index) in item.foto_ktp" :key="index">
                                <img :src="foto" 
                                     @click="openImgModal(foto, 'Lampiran KTP')"
                                     class="h-10 w-10 object-cover rounded shadow-sm border border-gray-200 cursor-zoom-in hover:opacity-80 transition">
                            </template>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span x-show="item.status === 'pending'" class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 uppercase">Pending</span>
                        <span x-show="item.status === 'selesai'" class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 uppercase">Selesai</span>
                        <span x-show="item.status === 'ditolak'" class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 uppercase">Ditolak</span>
                        
                        <div class="mt-1 text-[10px] text-gray-500 max-w-[150px] truncate" x-show="item.tanggapan_admin" x-text="'Ket: ' + item.tanggapan_admin"></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                            <template x-if="item.status === 'pending'">
                                <div class="flex space-x-2">
                                    <button @click="
                                        const note = prompt('Berikan tanggapan singkat:');
                                        if(note) submitAction(item.url_respon, { laporan_id: item.id, type: item.kat_row, admin_note: note })
                                    " class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-xs transition">
                                        Terima
                                    </button>
                                    <button @click="
                                        const note = prompt('Alasan penolakan:');
                                        if(note) submitAction(item.url_tolak, { laporan_id: item.id, type: item.kat_row, admin_note: note })
                                    " class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-xs transition">
                                        Tolak
                                    </button>
                                </div>
                            </template>

                            <button @click="submitAction(item.url_hapus, { laporan_id: item.id, type: item.kat_row, _method: 'DELETE' }, 'Hapus data ini?')" 
                                    class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            </template>

            <template x-if="filteredData.length === 0">
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                        Tidak ada data ditemukan untuk filter ini.
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>

<div class="mt-4 flex items-center justify-between">
    <div class="text-sm text-gray-700">
        Menampilkan <span class="font-bold" x-text="pagedData.length"></span> dari <span class="font-bold" x-text="filteredData.length"></span> data
    </div>
    <div class="flex space-x-2">
        <button @click="currentPage--" :disabled="currentPage === 1" 
                class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50 text-sm font-semibold">Prev</button>
        <div class="px-3 py-1 text-sm font-bold bg-blue-600 text-white rounded" x-text="currentPage"></div>
        <button @click="currentPage++" :disabled="currentPage >= totalPages" 
                class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50 text-sm font-semibold">Next</button>
    </div>
</div>