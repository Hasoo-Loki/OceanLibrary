@extends('layouts.admin')

@section('content')

<style>
    .font-playfair { font-family: 'Playfair Display', serif; }
    .ocean-input {
        background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
        border-radius: 10px; padding: .6rem 1rem; color: #e2e8f0;
        font-size: .875rem; width: 100%; outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .ocean-input::placeholder { color: #475569; }
    .ocean-input:focus { border-color: #22d3ee; box-shadow: 0 0 0 3px rgba(34,211,238,.1); }
    .ocean-input option { background: #0b1526; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp .5s ease both; }
    .particle { position:fixed; border-radius:50%; background:#22d3ee; pointer-events:none; z-index:0; opacity:0; animation: floatUp var(--d) var(--delay) infinite linear; }
    @keyframes floatUp { 0%{opacity:0;transform:translateY(0)} 10%{opacity:.4} 90%{opacity:.2} 100%{opacity:0;transform:translateY(-100vh)} }
</style>

<div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
    <div class="absolute w-[500px] h-[500px] rounded-full -top-40 -left-32"
         style="background:radial-gradient(circle,rgba(34,211,238,.05),transparent)"></div>
    <div class="absolute w-[400px] h-[400px] rounded-full -bottom-32 -right-20"
         style="background:radial-gradient(circle,rgba(56,189,248,.04),transparent)"></div>
</div>
<div id="ptc" class="fixed inset-0 z-0 pointer-events-none"></div>

<div class="relative z-10 p-7 min-h-screen" style="background:linear-gradient(160deg,#060d1a,#0a1628 60%,#071220)">

    {{-- HEADER --}}
    <div class="fade-up mb-7">
        <span class="inline-flex items-center gap-1.5 text-[10px] tracking-widest uppercase text-cyan-400 border border-cyan-400/25 bg-cyan-400/10 rounded-full px-3 py-1 mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
            Perpustakaan Digital Indonesia
        </span>
        <h1 class="font-playfair text-3xl mt-1"
            style="background:linear-gradient(135deg,#fff 30%,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
            Data Peminjaman
        </h1>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="fade-up mb-4 px-4 py-3 rounded-xl text-sm text-emerald-300 border border-emerald-400/20"
             style="background:rgba(52,211,153,.08)">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fade-up mb-4 px-4 py-3 rounded-xl text-sm text-red-300 border border-red-400/20"
             style="background:rgba(239,68,68,.08)">
            {{ session('error') }}
        </div>
    @endif

    {{-- FORM FILTER --}}
    <div class="fade-up rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:.08s">
        <form method="GET" action="{{ route('admin.peminjaman') }}" class="flex flex-wrap gap-3 items-end">

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Mulai</p>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="ocean-input" style="width:auto">
            </div>

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Akhir</p>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="ocean-input" style="width:auto">
            </div>

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Status</p>
                <select name="status" class="ocean-input" style="width:auto">
                    <option value="">Semua Status</option>
                    <option value="pending"               {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="dipinjam"              {{ request('status')=='dipinjam'?'selected':'' }}>Dipinjam</option>
                    <option value="menunggu_verifikasi"   {{ request('status')=='menunggu_verifikasi'?'selected':'' }}>Menunggu Verifikasi</option>
                    <option value="selesai"               {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Cari User <span class="normal-case text-slate-600">(nama/email)</span></p>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="ocean-input">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 rounded-xl text-sm font-semibold"
                    style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18;transition:box-shadow .2s"
                    onmouseover="this.style.boxShadow='0 0 18px rgba(34,211,238,.4)'"
                    onmouseout="this.style.boxShadow=''">Filter</button>

                <a href="{{ route('admin.peminjaman') }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 transition"
                   style="background:rgba(255,255,255,.06)">Reset</a>

                <a href="{{ route('admin.peminjaman.export', request()->all()) }}" target="_blank"
                   class="px-4 py-2 rounded-xl text-sm font-semibold text-red-300 transition"
                   style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2)"
                   onmouseover="this.style.background='rgba(239,68,68,.2)'"
                   onmouseout="this.style.background='rgba(239,68,68,.1)'">PDF</a>
            </div>
        </form>

        @if(request('start_date') || request('end_date'))
            <p class="text-xs text-slate-500 mt-3">
                Filter:
                @if(request('start_date')) <span class="text-cyan-400">{{ request('start_date') }}</span> @endif
                @if(request('start_date') && request('end_date')) s/d @endif
                @if(request('end_date')) <span class="text-cyan-400">{{ request('end_date') }}</span> @endif
                @if(request('status')) | Status: <span class="text-cyan-400">{{ ucfirst(request('status')) }}</span> @endif
            </p>
        @endif
    </div>

    {{-- TABEL --}}
    <div class="fade-up rounded-2xl overflow-x-auto" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:.12s">

        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="flex justify-between items-center px-6 pt-5 pb-3 text-xs text-slate-500 border-b border-white/5">
                <span>Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }} data</span>
                {{ $data->withQueryString()->links() }}
            </div>
        @endif

        <table class="w-full">
            <thead>
                <tr class="text-left text-[11px] uppercase tracking-wider text-slate-500 border-b border-white/5">
                    <th class="py-3 px-6">User</th>
                    <th class="px-4">Buku</th>
                    <th class="px-4">Tgl Pinjam</th>
                    <th class="px-4">Tgl Kembali</th>
                    <th class="px-4">Denda</th>
                    <th class="px-4">no telpn/th>
                    <th class="px-4">Status</th>
                    <th class="px-4">Bukti</th>
                    <th class="px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $p)
                <tr class="border-b border-white/5 transition hover:bg-white/[.03] {{ $p->is_terlambat ? 'border-l-2 border-red-500/60' : '' }}">

                    <td class="py-3 px-6">
                        <div class="text-sm font-medium text-slate-200">{{ $p->user->name ?? '-' }}</div>
                        <div class="text-[11px] text-slate-500">{{ $p->user->email ?? '' }}</div>
                    </td>

                    <td class="px-4 text-sm text-slate-300">{{ $p->book->judul ?? '-' }}</td>

                    <td class="px-4 text-sm text-slate-400">
                        {{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}
                    </td>

                    <td class="px-4 text-sm text-slate-400">
                        {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }}
                        @if($p->is_terlambat)
                            <div class="text-[11px] text-red-400 font-semibold mt-0.5">Terlambat {{ $p->hari_terlambat }} hari</div>
                        @endif
                    </td>

                                        <td class="px-4 text-sm">
                        @if($p->denda > 0)
                            <span class="text-red-300 font-semibold text-xs px-2 py-1 rounded-lg"
                                style="background:rgba(239,68,68,.1)">
                                Rp {{ number_format($p->denda, 0, ',', '.') }}
                            </span>
                        @elseif($p->status == 'selesai')
                            <span class="text-emerald-400 text-xs">Lunas</span>
                        @else
                            <span class="text-slate-600 text-xs">-</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                     <span class="text-[11px] text-slate-400">{{ $p->user->no_telp ?? '-' }}</span>
                    </td>


                    <td class="px-4">
                        @if($p->status == 'pending')
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-amber-300"
                                  style="background:rgba(251,191,36,.1)">Pending</span>
                        @elseif($p->status == 'dipinjam')
                            @if($p->is_terlambat)
                                <span class="text-[11px] font-bold px-2 py-1 rounded-lg text-red-300 animate-pulse"
                                      style="background:rgba(239,68,68,.12)">TERLAMBAT</span>
                            @else
                                <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-cyan-300"
                                      style="background:rgba(34,211,238,.1)">Dipinjam</span>
                            @endif
                        @elseif($p->status == 'menunggu_verifikasi')
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-purple-300"
                                  style="background:rgba(167,139,250,.1)">Menunggu Verifikasi</span>
                                  
                        @elseif($p->status == 'reject')
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-red-300"
                                  style="background:rgba(239,68,68,.1)">Ditolak</span>
                        @else
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-emerald-300"
                                  style="background:rgba(52,211,153,.1)">Selesai</span>
                        @endif
                    </td>

                    <td class="px-4">
                        @if($p->bukti)
                            <a href="{{ asset('images/bukti/' . $p->bukti) }}" target="_blank">
                                <img src="{{ asset('images/bukti/' . $p->bukti) }}"
                                     class="w-14 h-14 object-cover rounded-lg hover:opacity-75 transition"
                                     style="border:1px solid rgba(255,255,255,.1)">
                            </a>
                        @else
                            <span class="text-slate-600 text-xs">-</span>
                        @endif
                    </td>

                    <td class="px-4">
                        @if($p->status == 'pending')
                            <a href="{{ url('/admin/approve/' . $p->id) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-300 transition"
                               style="background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.2)"
                               onmouseover="this.style.background='rgba(52,211,153,.2)'"
                               onmouseout="this.style.background='rgba(52,211,153,.1)'">Approve</a>


                        
                            <a href ="{{ url('/admin/reject/' . $p->id) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-semibold text-red-300 transition"
                               style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2)"
                               onmouseover="this.style.background='rgba(239,68,68,.2)'"
                               onmouseout="this.style.background='rgba(239,68,68,.1)'">Reject</a>
                            
                         @elseif($p->status == 'menunggu_verifikasi')
                            <a href="{{ url('/admin/konfirmasi/' . $p->id) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-semibold text-purple-300 transition"
                               style="background:rgba(167,139,250,.1);border:1px solid rgba(167,139,250,.2)"
                               onmouseover="this.style.background='rgba(167,139,250,.2)'"
                               onmouseout="this.style.background='rgba(167,139,250,.1)'">Konfirmasi</a>
                        @else
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-600 text-sm">Tidak ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-6 py-4 border-t border-white/5">
                {{ $data->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    const c = document.getElementById('ptc');
    for (let i = 0; i < 30; i++) {
        const p = document.createElement('div'), s = 1 + Math.random() * 2;
        p.className = 'particle';
        p.style.cssText = `left:${Math.random()*100}%;top:${Math.random()*100}%;width:${s}px;height:${s}px;--d:${7+Math.random()*9}s;--delay:${Math.random()*10}s`;
        c.appendChild(p);
    }
</script>

@endsection