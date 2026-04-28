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
    .ocean-input::placeholder { color: #334155; }
    .ocean-input:focus { border-color: #22d3ee; box-shadow: 0 0 0 3px rgba(34,211,238,.1); }
    .ocean-input option { background: #0b1526; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
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
            Edit Buku
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

    {{-- CARD FORM --}}
    <div class="fade-up rounded-2xl p-6 max-w-2xl" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:.08s">

        <p class="text-[10px] uppercase tracking-widest text-cyan-400 mb-5">Informasi Buku</p>

        <form action="/admin/books/update/{{ $book->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- JUDUL --}}
            <div class="mb-4">
                <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Judul</label>
                <input type="text" name="judul" value="{{ $book->judul }}" required class="ocean-input" placeholder="Judul buku...">
            </div>

            {{-- PENULIS --}}
            <div class="mb-4">
                <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Penulis</label>
                <input type="text" name="penulis" value="{{ $book->penulis }}" required class="ocean-input" placeholder="Nama penulis...">
            </div>

            {{-- KATEGORI --}}
            <div class="mb-4">
                <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Kategori</label>
                <select name="kategori" required class="ocean-input">
                    @foreach($kategoris as $k)
                        <option value="{{ $k->nama }}" {{ $book->kategori == $k->nama ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- TAHUN & STOK --}}
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Tahun</label>
                    <input type="number" name="tahun" value="{{ $book->tahun }}" class="ocean-input" placeholder="Tahun terbit">
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Stok</label>
                    <input type="number" name="stock" value="{{ $book->stock }}" min="0" class="ocean-input" placeholder="Jumlah stok">
                </div>
            </div>

            {{-- SINOPSIS --}}
            <div class="mb-5">
                <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Sinopsis</label>
                <textarea name="sinopsis" rows="4" class="ocean-input" placeholder="Tulis sinopsis...">{{ $book->sinopsis }}</textarea>
            </div>

            {{-- DIVIDER --}}
            <div class="border-t border-white/5 my-5"></div>
            <p class="text-[10px] uppercase tracking-widest text-cyan-400 mb-4">Gambar Buku</p>

            {{-- GAMBAR SAAT INI --}}
            <div class="mb-4">
                <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">Gambar Saat Ini</label>
                <img src="{{ asset('images/' . $book->gambar) }}" alt="{{ $book->judul }}"
                     class="w-20 h-28 object-cover rounded-lg"
                     style="border:1px solid rgba(255,255,255,.1)">
            </div>

            {{-- GANTI GAMBAR --}}
            <div class="mb-6">
                <label class="block text-[11px] uppercase tracking-wider text-slate-500 mb-1.5">
                    Ganti Gambar <span class="normal-case text-slate-600">(opsional)</span>
                </label>
                <input type="file" name="gambar" accept="image/*" class="ocean-input"
                       style="padding:.5rem .75rem;color:#475569">
            </div>

            {{-- TOMBOL --}}
            <div class="flex gap-3">
                <button type="submit"
                    class="px-5 py-2 rounded-xl text-sm font-semibold"
                    style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18"
                    onmouseover="this.style.boxShadow='0 0 18px rgba(34,211,238,.4)'"
                    onmouseout="this.style.boxShadow=''">Update Buku</button>

                <a href="{{ url()->previous() }}"
                   class="px-5 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-slate-200 transition"
                   style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08)">Batal</a>
            </div>

        </form>
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