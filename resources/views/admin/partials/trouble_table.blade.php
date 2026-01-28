<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">User</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">Kategori</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">Deskripsi</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">Waktu</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($troubles as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <div class="text-sm font-bold text-slate-700">{{ $item->user->name ?? 'N/A' }}</div>
                        <div class="text-[10px] text-slate-400">IP: {{ $item->ip_address ?? '-' }}</div>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 text-[10px] font-extrabold bg-slate-100 text-slate-600 rounded border border-slate-200">
                            {{ strtoupper($item->kategori ?? 'UMUM') }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-slate-600 max-w-xs truncate">
                        {{ $item->deskripsi }}
                    </td>
                    <td class="p-4 text-xs text-slate-500 italic">
                        {{ $item->created_at->format('d/m H:i') }}
                    </td>
                    <td class="p-4 text-center">
                        <button onclick="openModal('{{ $item->id }}')" class="text-blue-500 hover:text-blue-700 font-bold text-xs uppercase shadow-sm border border-blue-200 px-3 py-1 rounded-lg bg-white">
                            Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400 text-sm">Tidak ada laporan gangguan saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center bg-slate-50 p-3 border-t border-slate-200">
        <span class="text-[9px] font-black text-slate-500 ml-2 uppercase">
            Halaman {{ $troubles->currentPage() }} dari {{ $troubles->lastPage() }}
        </span>
        <div class="ajax-pagination" data-target="trouble">
            {{ $troubles->appends(['target' => 'trouble'])->links() }}
        </div>
    </div>
</div>