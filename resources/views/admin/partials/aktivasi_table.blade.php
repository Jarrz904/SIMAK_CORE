<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">Pelapor</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">NIK Aktivasi</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">Layanan</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500">Tanggal</th>
                    <th class="p-4 text-xs font-black uppercase text-slate-500 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($aktivasis as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4">
                        <div class="text-sm font-bold text-slate-700">{{ $item->user->name ?? 'N/A' }}</div>
                        <div class="text-[10px] text-slate-400">{{ $item->user->location ?? '-' }}</div>
                    </td>
                    <td class="p-4 text-sm font-medium text-slate-600">{{ $item->nik_aktivasi }}</td>
                    <td class="p-4 text-sm text-slate-600">{{ strtoupper($item->jenis_layanan) }}</td>
                    <td class="p-4 text-xs text-slate-500">{{ $item->created_at->diffForHumans() }}</td>
                    <td class="p-4 text-center">
                        @if($item->tanggapan_admin)
                            <span class="px-2 py-1 text-[10px] font-bold bg-green-100 text-green-600 rounded-full">SELESAI</span>
                        @else
                            <span class="px-2 py-1 text-[10px] font-bold bg-amber-100 text-amber-600 rounded-full">PENDING</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-400 text-sm">Belum ada data laporan aktivasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center bg-slate-50 p-3 border-t border-slate-200">
        <span class="text-[9px] font-black text-slate-500 ml-2 uppercase">
            Halaman {{ $aktivasis->currentPage() }} dari {{ $aktivasis->lastPage() }}
        </span>
        <div class="ajax-pagination" data-target="aktivasi">
            {{ $aktivasis->appends(['target' => 'aktivasi'])->links() }}
        </div>
    </div>
</div>