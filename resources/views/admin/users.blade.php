@extends('layouts.admin')

@section('content')

<style>
    .font-playfair { font-family: 'Playfair Display', serif; }
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
            Data User
        </h1>
    </div>

    {{-- TABEL --}}
    <div class="fade-up rounded-2xl overflow-x-auto" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:.08s">
        <table class="w-full">
            <thead>
                <tr class="text-left text-[11px] uppercase tracking-wider text-slate-500 border-b border-white/5">
                    <th class="py-3 px-6">Nama</th>
                    <th class="px-4">Email</th>
                    <th class="px-4">Status</th>
                    <th class="px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr class="border-b border-white/5 hover:bg-white/[.03] transition">

                    <td class="py-3 px-6 text-sm font-medium text-slate-200">{{ $u->name ?? '-' }}</td>

                    <td class="px-4 text-sm text-slate-400">{{ $u->email }}</td>

                    <td class="px-4">
                        @if($u->is_member)
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-emerald-300"
                                  style="background:rgba(52,211,153,.1)">Member</span>
                        @else
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-lg text-red-300"
                                  style="background:rgba(239,68,68,.1)">Non Member</span>
                        @endif
                    </td>

                    <td class="px-4">
                        <a href="/admin/member/{{ $u->id }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                           {{ $u->is_member ? 'text-red-300' : 'text-cyan-300' }}"
                           style="{{ $u->is_member
                               ? 'background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2)'
                               : 'background:rgba(34,211,238,.1);border:1px solid rgba(34,211,238,.2)' }}"
                           onmouseover="this.style.opacity='.75'"
                           onmouseout="this.style.opacity='1'">
                            {{ $u->is_member ? 'Nonaktifkan' : 'Jadikan Member' }}
                        </a>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
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