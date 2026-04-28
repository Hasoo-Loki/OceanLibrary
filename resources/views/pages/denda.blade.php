@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-br from-[#0a1628] via-[#0d2444] to-[#0a3060] px-6 py-10">
    <div class="max-w-6xl mx-auto">

        <div class="flex items-center gap-2 mb-1">
            <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
            <span class="text-red-400 text-xs uppercase tracking-widest">Denda Anda</span>
        </div>

        <h1 class="text-white text-2xl font-semibold mb-1">Data Denda</h1>
        <p class="text-slate-500 text-sm mb-6">Pantau semua denda yang Anda terima dari admin</p>

        <div class="h-px bg-gradient-to-r from-transparent via-red-500/40 to-transparent mb-8"></div>

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

        {{-- SUMMARY CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-8">
            <div class="bg-red-500/10 border border-red-500/25 rounded-xl px-4 py-3 hover:bg-red-500/[0.15] transition">
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-1">Belum Lunas</p>
                <p class="text-red-400 text-2xl font-semibold">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-500/10 border border-green-500/25 rounded-xl px-4 py-3 hover:bg-green-500/[0.15] transition">
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-1">Sudah Lunas</p>
                <p class="text-green-400 text-2xl font-semibold">Rp {{ number_format($totalLunas, 0, ',', '.') }}</p>
            </div>
            <div class="bg-cyan-500/10 border border-cyan-500/25 rounded-xl px-4 py-3 hover:bg-cyan-500/[0.15] transition">
                <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-1">Total Kasus</p>
                <p class="text-cyan-400 text-2xl font-semibold">{{ $data->count() }}</p>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="bg-white/[0.03] border border-white/10 rounded-2xl p-5 mb-6">
            <form method="GET" action="{{ route('user.denda') }}" class="flex flex-wrap gap-3 items-end">
                <div>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Jenis</p>
                    <select name="jenis" class="bg-white/5 border border-white/10 rounded-lg text-slate-300 px-3 py-2 text-sm w-40">
                        <option value="">Semua</option>
                        <option value="terlambat" {{ request('jenis')=='terlambat'?'selected':'' }}>Terlambat</option>
                        <option value="rusak"     {{ request('jenis')=='rusak'?'selected':'' }}>Rusak</option>
                        <option value="hilang"    {{ request('jenis')=='hilang'?'selected':'' }}>Hilang</option>
                    </select>
                </div>
                <div>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Status</p>
                    <select name="status" class="bg-white/5 border border-white/10 rounded-lg text-slate-300 px-3 py-2 text-sm w-40">
                        <option value="">Semua</option>
                        <option value="belum_lunas" {{ request('status')=='belum_lunas'?'selected':'' }}>Belum Lunas</option>
                        <option value="lunas"       {{ request('status')=='lunas'?'selected':'' }}>Lunas</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-cyan-600 hover:bg-cyan-700 transition">Filter</button>
                <a href="{{ route('user.denda') }}" class="px-4 py-2 rounded-lg text-sm text-slate-400 hover:text-slate-200 bg-white/5 transition">Reset</a>
            </form>
        </div>

        {{-- TABEL --}}
        <div class="bg-white/[0.03] border border-white/10 rounded-2xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10">
                        <th class="px-5 py-4 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Jenis</th>
                        <th class="px-5 py-4 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Nominal</th>
                        <th class="px-5 py-4 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Keterangan</th>
                        <th class="px-5 py-4 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Bukti</th>
                        <th class="px-5 py-4 text-center text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-center text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $d)
                    <tr class="border-t border-white/5 hover:bg-white/[0.025] transition">

                        <td class="px-5 py-4">
                            @if($d->jenis == 'terlambat')
                                <span class="text-amber-400 text-sm font-semibold">⏰ Terlambat</span>
                            @elseif($d->jenis == 'rusak')
                                <span class="text-orange-400 text-sm font-semibold">🔧 Rusak</span>
                            @else
                                <span class="text-red-400 text-sm font-semibold">❌ Hilang</span>
                            @endif
                        </td>

                        <td class="px-5 py-4">
                            <span class="text-red-300 font-semibold text-sm">Rp {{ number_format($d->jumlah, 0, ',', '.') }}</span>
                        </td>

                        <td class="px-5 py-4 text-slate-400 text-sm max-w-xs">
                            {{ $d->keterangan ?: '-' }}
                        </td>

                        <td class="px-5 py-4">
                            @if($d->bukti)
                                <a href="{{ asset('images/bukti_denda/'.$d->bukti) }}" target="_blank" 
                                   class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded-lg text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 transition border border-blue-500/25">
                                    📷 Lihat
                                </a>
                            @else
                                <span class="text-slate-600 text-xs">-</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-center">
                            @if($d->status == 'belum_lunas')
                                <span class="bg-red-500/10 text-red-400 text-[11px] font-semibold px-3 py-1 rounded-full inline-block">Belum Lunas</span>
                            @else
                                <span class="bg-green-500/10 text-green-400 text-[11px] font-semibold px-3 py-1 rounded-full inline-block">✓ Lunas</span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-center text-slate-500 text-sm">
                            {{ $d->created_at->format('d/m/Y') }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="text-4xl mb-3">✓</div>
                            <p class="text-slate-600 text-sm">Tidak ada denda. Bagus sekali!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection
