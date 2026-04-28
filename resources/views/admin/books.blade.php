@extends('layouts.admin')

@section('content')

<style>
    .font-playfair { font-family: 'Playfair Display', serif; }
    .ocean-input {
        background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
        border-radius: 10px; padding: .6rem 1rem; color: #e2e8f0;
        font-size: .875rem; width: 100%; outline: none;
        transition: border-color .2s, box-shadow .2s; font-family: 'DM Sans', sans-serif;
    }
    .ocean-input::placeholder { color: #475569; }
    .ocean-input:focus { border-color: #22d3ee; box-shadow: 0 0 0 3px rgba(34,211,238,.1); }
    .ocean-input option { background: #0b1526; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp .5s ease both; }
    .particle { position:fixed; border-radius:50%; background:#22d3ee; pointer-events:none; z-index:0; opacity:0; animation: floatUp var(--d) var(--delay) infinite linear; }
    @keyframes floatUp { 0%{opacity:0;transform:translateY(0)} 10%{opacity:.4} 90%{opacity:.2} 100%{opacity:0;transform:translateY(-100vh)} }
</style>

{{-- Ambient blobs --}}
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
            Data Buku
        </h1>
    </div>

    {{-- DAFTAR BUKU --}}
    <div class="fade-up flex items-center gap-3 mb-5" style="animation-delay:.1s">
        <h2 class="font-playfair text-xl"
            style="background:linear-gradient(135deg,#fff,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
            Koleksi Buku
        </h2>
        <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(34,211,238,.35),transparent)"></div>
        <span class="text-xs text-slate-500">{{ count($books) }} buku</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($books as $i => $book)
        <div class="fade-up flex flex-col rounded-2xl overflow-hidden"
             style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);animation-delay:{{ .15 + $i * .04 }}s;transition:transform .3s,border-color .3s,box-shadow .3s"
             onmouseover="this.style.transform='translateY(-5px)';this.style.borderColor='rgba(34,211,238,.28)';this.style.boxShadow='0 0 20px rgba(34,211,238,.08),0 12px 28px rgba(0,0,0,.4)'"
             onmouseout="this.style.transform='';this.style.borderColor='rgba(255,255,255,.06)';this.style.boxShadow=''">

            <div class="h-40 flex items-center justify-center relative overflow-hidden"
                 style="border-bottom:1px solid rgba(255,255,255,.06)">
                <img src="{{ asset('images/' . $book->gambar) }}" alt="{{ $book->judul }}"
                     class="max-h-full object-contain transition-transform duration-500 hover:scale-105">
                @if($book->stock == 0)
                    <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-full"
                          style="background:rgba(239,68,68,.85);color:#fff">Habis</span>
                @endif
            </div>

            <div class="p-3 flex flex-col flex-1">
                <p class="text-xs font-semibold text-slate-200 line-clamp-2 leading-snug">{{ $book->judul }}</p>
                <p class="text-[11px] text-slate-500 mt-1">{{ $book->penulis }}</p>
                <p class="text-[11px] text-cyan-400/80 mt-0.5 font-medium">{{ $book->kategori->nama ?? '-' }}</p>
                @if($book->tahun)
                    <p class="text-[10px] text-slate-600 mt-0.5">{{ $book->tahun }}</p>
                @endif

                <div class="flex justify-between mt-2 px-2 py-1.5 rounded-lg text-[10px] font-semibold"
                     style="background:rgba(255,255,255,.04)">
                    <span class="{{ $book->stock > 0 ? 'text-emerald-400' : 'text-red-400' }}">📦 {{ $book->stock }}</span>
                    <span class="{{ $book->tersedia ? 'text-emerald-400' : 'text-red-400' }}">
                        {{ $book->tersedia ? '✓ Tersedia' : '✗ Dipinjam' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-1.5 mt-2.5">
                    <a href="/admin/books/edit/{{ $book->id }}"
                       class="text-center py-1.5 rounded-lg text-[11px] font-semibold"
                       style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18;transition:box-shadow .2s"
                       onmouseover="this.style.boxShadow='0 0 10px rgba(34,211,238,.4)'"
                       onmouseout="this.style.boxShadow=''">✎ Edit</a>
                    <a href="/admin/books/delete/{{ $book->id }}"
                       class="text-center py-1.5 rounded-lg text-[11px] font-medium text-red-400 transition"
                       style="background:rgba(239,68,68,.08)"
                       onmouseover="this.style.background='rgba(239,68,68,.18)'"
                       onmouseout="this.style.background='rgba(239,68,68,.08)'"
                       onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑 Hapus</a>
                </div>
            </div>
        </div>
        @endforeach
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