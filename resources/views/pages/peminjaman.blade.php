@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-br from-[#0a1628] via-[#0d2444] to-[#0a3060] px-6 py-10">
    <div class="max-w-6xl mx-auto">

        <div class="flex items-center gap-2 mb-1">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
            <span class="text-cyan-400 text-xs uppercase tracking-widest">Perpustakaan Digital</span>
        </div>

        <h1 class="text-white text-2xl font-semibold mb-1">Riwayat Peminjaman</h1>
        <p class="text-slate-500 text-sm mb-6">Lacak semua aktivitas peminjaman bukumu</p>

        <div class="h-px bg-gradient-to-r from-transparent via-cyan-500/40 to-transparent mb-8"></div>

        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/25 text-green-400 px-4 py-3 rounded-xl text-sm mb-6">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/25 text-red-400 px-4 py-3 rounded-xl text-sm mb-6">
                ✗ {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
            <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/[0.07] transition">
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-1">Sedang Dipinjam</p>
                <p class="text-cyan-400 text-2xl font-semibold">{{ $data->where('status','dipinjam')->count() }}</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/[0.07] transition">
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-1">Menunggu</p>
                <p class="text-yellow-400 text-2xl font-semibold">{{ $data->whereIn('status',['pending','menunggu_verifikasi'])->count() }}</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-3 hover:bg-white/[0.07] transition">
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-1">Selesai</p>
                <p class="text-green-400 text-2xl font-semibold">{{ $data->where('status','selesai')->count() }}</p>
            </div>
            
        </div>

        <div class="bg-white/[0.03] border border-white/10 rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="px-5 py-4 text-left text-[11px] font-semibold text-cyan-800 uppercase tracking-wider">Judul Buku</th>
                        <th class="px-5 py-4 text-center text-[11px] font-semibold text-cyan-800 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-5 py-4 text-center text-[11px] font-semibold text-cyan-800 uppercase tracking-wider">Tanggal Kembali</th>
                        <th class="px-5 py-4 text-center text-[11px] font-semibold text-cyan-800 uppercase tracking-wider">Total Denda</th>
                        <th class="px-5 py-4 text-center text-[11px] font-semibold text-cyan-800 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-center text-[11px] font-semibold text-cyan-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $p)
                    <tr class="border-t border-white/5 hover:bg-white/[0.025] transition">

                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-cyan-400/10 text-cyan-400 flex items-center justify-center text-sm shrink-0">📖</span>
                                <span class="text-slate-200 font-medium text-sm">{{ $p->book->judul }}</span>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center text-slate-500 text-sm">
                            {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                        </td>

                        <td class="px-5 py-4 text-center text-slate-500 text-sm">
                            {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') : '—' }}
                        </td>

                        <td class="px-5 py-4 text-center">
                            @php
                                $totalDenda = 0;
                                if ($p->denda && $p->denda->count() > 0) {
                                    $totalDenda = $p->denda->where('status', 'belum_lunas')->sum('jumlah');
                                }
                            @endphp
                            @if($totalDenda > 0)
                                <div>
                                    <span class="bg-red-500/20 text-red-400 text-[11px] font-semibold px-3 py-1 rounded-full">
                                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <span class="text-slate-500 text-sm">—</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($p->status == 'pending')
                                <span class="bg-yellow-400/10 text-yellow-400 text-[11px] font-semibold px-3 py-1 rounded-full">Menunggu Approval</span>
                            @elseif($p->status == 'dipinjam')
                                <span class="bg-cyan-400/10 text-cyan-400 text-[11px] font-semibold px-3 py-1 rounded-full">Dipinjam</span>
                            @elseif($p->status == 'menunggu_verifikasi')
                                <span class="bg-purple-400/10 text-purple-400 text-[11px] font-semibold px-3 py-1 rounded-full">Menunggu Verifikasi</span>
                            @elseif($p->status == 'reject')
                                <span class="bg-red-500/10 text-red-400 text-[11px] font-semibold px-3 py-1 rounded-full">Ditolak</span>
                            @else
                                <span class="bg-green-400/10 text-green-400 text-[11px] font-semibold px-3 py-1 rounded-full">Selesai</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($p->status == 'dipinjam')
                                <form action="/kembalikan/{{ $p->id }}" method="POST"
                                      enctype="multipart/form-data"
                                      class="flex flex-col items-center gap-2">
                                    @csrf

                                    <input type="file" name="bukti"
                                           id="bukti_{{ $p->id }}"
                                           class="hidden"
                                           onchange="updateFileName({{ $p->id }})">

                                    <label for="bukti_{{ $p->id }}"
                                           class="cursor-pointer bg-white/[0.07] hover:bg-white/[0.13] border border-white/15 text-slate-400 text-[11px] px-3 py-1.5 rounded-lg transition whitespace-nowrap">
                                        📷 Pilih Foto
                                    </label>

                                    <span id="file_name_{{ $p->id }}"
                                          class="text-slate-600 text-[10px] min-h-[14px]">
                                        Belum ada foto
                                    </span>

                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-semibold px-4 py-1.5 rounded-lg transition whitespace-nowrap">
                                        Kembalikan
                                    </button>
                                </form>

                            @elseif($p->status == 'menunggu_verifikasi')
                                <span class="text-purple-400/70 text-[11px]">Menunggu admin...</span>

                            @else
                                <span class="text-slate-700">—</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="text-4xl mb-3">📚</div>
                            <p class="text-slate-600 text-sm">Belum ada riwayat peminjaman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function updateFileName(id) {
    const input = document.getElementById('bukti_' + id);
    const span  = document.getElementById('file_name_' + id);

    if (input.files && input.files[0]) {
        let name = input.files[0].name;
        if (name.length > 22) name = name.substring(0, 19) + '...';
        span.textContent = '📎 ' + name;
        span.classList.add('text-cyan-400');
        span.classList.remove('text-slate-600');
    } else {
        span.textContent = 'Belum ada foto';
        span.classList.remove('text-cyan-400');
        span.classList.add('text-slate-600');
    }
}
</script>
@endpush