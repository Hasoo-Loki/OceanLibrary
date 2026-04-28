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
    @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
    .fade-up { animation: fadeUp .5s ease both; }
</style>

<div class="relative z-10 p-7 min-h-screen" style="background:linear-gradient(160deg,#060d1a,#0a1628 60%,#071220)">

    {{-- HEADER --}}
    <div class="fade-up mb-7">
        <span class="inline-flex items-center gap-1.5 text-[10px] tracking-widest uppercase text-cyan-400 border border-cyan-400/25 bg-cyan-400/10 rounded-full px-3 py-1 mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
            Manajemen Denda
        </span>
        <h1 class="font-playfair text-3xl mt-1"
            style="background:linear-gradient(135deg,#fff 30%,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
            Data Denda User
        </h1>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="fade-up mb-4 px-4 py-3 rounded-xl text-sm text-emerald-300 border border-emerald-400/20"
             style="background:rgba(52,211,153,.08)">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="fade-up mb-4 px-4 py-3 rounded-xl text-sm text-red-300 border border-red-400/20"
             style="background:rgba(239,68,68,.08)">{{ session('error') }}</div>
    @endif

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-7 fade-up">
        <div class="rounded-2xl p-5" style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2)">
            <p class="text-[11px] uppercase tracking-wider text-red-400/70 mb-1">Total Belum Lunas</p>
            <p class="text-2xl font-bold text-red-300">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.2)">
            <p class="text-[11px] uppercase tracking-wider text-emerald-400/70 mb-1">Total Sudah Lunas</p>
            <p class="text-2xl font-bold text-emerald-300">Rp {{ number_format($totalLunas, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background:rgba(34,211,238,.06);border:1px solid rgba(34,211,238,.15)">
            <p class="text-[11px] uppercase tracking-wider text-cyan-400/70 mb-1">Total Kasus</p>
            <p class="text-2xl font-bold text-cyan-300">{{ $data->count() }}</p>
        </div>
    </div>

    {{-- FORM TAMBAH DENDA --}}
    <div class="fade-up rounded-2xl p-6 mb-6" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07)">
        <h2 class="text-sm font-semibold text-cyan-400 mb-4 tracking-wide uppercase">+ Tambah Denda</h2>
        <form method="POST" action="{{ route('admin.denda.store') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" enctype="multipart/form-data">
            @csrf

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">User</p>
                <select name="user_id" class="ocean-input" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Peminjaman (opsional)</p>
                <select name="peminjaman_id" class="ocean-input">
                    <option value="">-- Tidak terkait peminjaman --</option>
                    @foreach($peminjaman as $pm)
                        <option value="{{ $pm->id }}">
                            #{{ $pm->id }} — {{ $pm->user->name ?? '-' }} · {{ $pm->book->judul ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Jenis Denda</p>
                <select name="jenis" class="ocean-input" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="terlambat">⏰ Terlambat</option>
                    <option value="rusak">🔧 Buku Rusak</option>
                    <option value="hilang">❌ Buku Hilang</option>
                </select>
            </div>

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Nominal (Rp)</p>
                <input type="number" name="jumlah" min="1000" step="1000"
                       placeholder="Contoh: 10000" class="ocean-input" required>
            </div>

            <div class="md:col-span-2">
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Keterangan</p>
                <input type="text" name="keterangan"
                       placeholder="Misal: Terlambat 3 hari, cover sobek, dll"
                       class="ocean-input">
            </div>

            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Bukti Foto (opsional)</p>
                <input type="file" name="bukti"
                       accept="image/*" class="ocean-input"
                       style="padding: .5rem .75rem;">
            </div>

            <div class="flex items-end lg:col-span-3">
                <button type="submit"
                        class="w-full px-4 py-2.5 rounded-xl text-sm font-semibold transition"
                        style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18"
                        onmouseover="this.style.boxShadow='0 0 18px rgba(34,211,238,.4)'"
                        onmouseout="this.style.boxShadow=''">
                    Tambah Denda & Kirim Notif
                </button>
            </div>
        </form>
    </div>

    {{-- FILTER --}}
    <div class="fade-up rounded-2xl p-5 mb-6" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07)">
        <form method="GET" action="{{ route('admin.denda') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Cari User</p>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama atau email..." class="ocean-input" style="width:220px">
            </div>
            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Jenis</p>
                <select name="jenis" class="ocean-input" style="width:auto">
                    <option value="">Semua</option>
                    <option value="terlambat" {{ request('jenis')=='terlambat'?'selected':'' }}>Terlambat</option>
                    <option value="rusak"     {{ request('jenis')=='rusak'?'selected':'' }}>Rusak</option>
                    <option value="hilang"    {{ request('jenis')=='hilang'?'selected':'' }}>Hilang</option>
                </select>
            </div>
            <div>
                <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Status</p>
                <select name="status" class="ocean-input" style="width:auto">
                    <option value="">Semua</option>
                    <option value="belum_lunas" {{ request('status')=='belum_lunas'?'selected':'' }}>Belum Lunas</option>
                    <option value="lunas"       {{ request('status')=='lunas'?'selected':'' }}>Lunas</option>
                </select>
            </div>
            <button type="submit"
                    class="px-4 py-2 rounded-xl text-sm font-semibold"
                    style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18">Filter</button>
            <a href="{{ route('admin.denda') }}"
               class="px-4 py-2 rounded-xl text-sm text-slate-400 hover:text-slate-200 transition"
               style="background:rgba(255,255,255,.06)">Reset</a>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="fade-up rounded-2xl overflow-x-auto" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07)">
        <table class="w-full">
            <thead>
                <tr class="text-left text-[11px] uppercase tracking-wider text-slate-500 border-b border-white/5">
                    <th class="py-3 px-6">User</th>
                    <th class="px-4">Buku</th>
                    <th class="px-4">Jenis</th>
                    <th class="px-4">Nominal</th>
                    <th class="px-4">Keterangan</th>
                    <th class="px-4">Bukti</th>
                    <th class="px-4">Status</th>
                    <th class="px-4">Tanggal</th>
                    <th class="px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr class="border-b border-white/5 hover:bg-white/[.03] transition">

                    <td class="py-3 px-6">
                        <div class="text-sm font-medium text-slate-200">{{ $d->user->name ?? '-' }}</div>
                        <div class="text-[11px] text-slate-500">{{ $d->user->email ?? '' }}</div>
                    </td>

                    <td class="px-4 text-sm text-slate-400">
                        @if($d->peminjaman)
                            <span class="text-slate-300">{{ $d->peminjaman->book->judul ?? '-' }}</span>
                            <div class="text-[10px] text-slate-600">#{{ $d->peminjaman_id }}</div>
                        @else
                            <span class="text-slate-600">-</span>
                        @endif
                    </td>

                    <td class="px-4">
                        @if($d->jenis == 'terlambat')
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-amber-300"
                                  style="background:rgba(251,191,36,.1)">⏰ Terlambat</span>
                        @elseif($d->jenis == 'rusak')
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-orange-300"
                                  style="background:rgba(251,146,60,.1)">🔧 Rusak</span>
                        @else
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-red-300"
                                  style="background:rgba(239,68,68,.1)">❌ Hilang</span>
                        @endif
                    </td>

                    <td class="px-4 text-sm font-semibold text-red-300">
                        Rp {{ number_format($d->jumlah, 0, ',', '.') }}
                    </td>

                    <td class="px-4 text-sm text-slate-400 max-w-[200px]">
                        {{ $d->keterangan ?: '-' }}
                    </td>

                    <td class="px-4">
                        @if($d->bukti)
                            <a href="{{ asset('images/bukti_denda/'.$d->bukti) }}" target="_blank" 
                               class="inline-flex items-center gap-1.5 text-xs font-medium px-2 py-1 rounded-lg text-blue-300 transition"
                               style="background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.2)"
                               onmouseover="this.style.background='rgba(59,130,246,.2)'"
                               onmouseout="this.style.background='rgba(59,130,246,.1)'">
                                📷 Lihat Bukti
                            </a>
                        @else
                            <span class="text-slate-600 text-xs">-</span>
                        @endif
                    </td>

                    <td class="px-4">
                        @if($d->status == 'belum_lunas')
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-red-300"
                                  style="background:rgba(239,68,68,.1)">Belum Lunas</span>
                        @else
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-emerald-300"
                                  style="background:rgba(52,211,153,.1)">✓ Lunas</span>
                        @endif
                    </td>

                    <td class="px-4 text-sm text-slate-500">
                        {{ $d->created_at->format('d/m/Y') }}
                    </td>

                    <td class="px-4">
                        <div class="flex gap-2">
                            @if($d->status == 'belum_lunas')
                                <a href="{{ route('admin.denda.lunas', $d->id) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-300 transition"
                                   style="background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.2)"
                                   onmouseover="this.style.background='rgba(52,211,153,.2)'"
                                   onmouseout="this.style.background='rgba(52,211,153,.1)'">Lunas</a>
                            @endif
                            <form method="POST" action="{{ route('admin.denda.destroy', $d->id) }}"
                                  onsubmit="return confirm('Hapus data denda ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-red-300 transition"
                                        style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2)"
                                        onmouseover="this.style.background='rgba(239,68,68,.2)'"
                                        onmouseout="this.style.background='rgba(239,68,68,.1)'">Hapus</button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-12 text-center text-slate-600 text-sm">
                        Belum ada data denda
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection