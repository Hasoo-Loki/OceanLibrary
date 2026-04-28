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
            Kelola Kategori
        </h1>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="fade-up mb-5 px-4 py-3 rounded-xl text-sm text-emerald-300 border border-emerald-400/20"
             style="background:rgba(52,211,153,.08)">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- FORM TAMBAH --}}
        <div class="fade-up rounded-2xl p-6" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:.08s">
            <h2 class="font-playfair text-xl text-cyan-400 mb-5">✦ Tambah Kategori</h2>

            <form method="POST" action="/admin/kategori">
                @csrf
                <div class="mb-4">
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Nama Kategori</p>
                    <input type="text" name="nama" placeholder="Nama kategori..." class="ocean-input">
                </div>
                <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold"
                    style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18;transition:box-shadow .2s"
                    onmouseover="this.style.boxShadow='0 0 20px rgba(34,211,238,.4)'"
                    onmouseout="this.style.boxShadow=''">
                    ✦ Tambah
                </button>
            </form>
        </div>

        {{-- DAFTAR KATEGORI --}}
        <div class="fade-up rounded-2xl overflow-hidden" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:.12s">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-white/5">
                <h2 class="font-playfair text-xl"
                    style="background:linear-gradient(135deg,#fff,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                    Daftar Kategori
                </h2>
                <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(34,211,238,.3),transparent)"></div>
                <span class="text-xs text-slate-500">{{ $data->count() }} kategori</span>
            </div>

            <table class="w-full">
                <tbody>
                    @forelse($data as $k)
                    <tr class="border-b border-white/5 hover:bg-white/[.03] transition">
                        <td class="py-3 px-6 text-sm text-slate-300">{{ $k->nama }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="py-10 text-center text-slate-600 text-sm">Belum ada kategori</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

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