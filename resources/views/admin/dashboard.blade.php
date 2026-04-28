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
    input[type="file"].ocean-input::-webkit-file-upload-button {
        background: rgba(34,211,238,.12); border: 1px solid rgba(34,211,238,.3);
        color: #22d3ee; border-radius: 6px; padding: 3px 10px; font-size: .75rem; cursor: pointer;
    }
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
            Dashboard Admin
        </h1>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        @php $stats = [
            ['Total Buku',       $totalBuku,   '📚','#22d3ee','rgba(34,211,238,.18)'],
            ['Total User',       $totalUser,   '👥','#38bdf8','rgba(56,189,248,.18)'],
            ['Total Peminjaman', $totalPinjam, '🔖','#818cf8','rgba(99,102,241,.18)'],
        ]; @endphp

        @foreach($stats as $i=>[$label,$val,$icon,$col,$glow])
        <div class="fade-up relative rounded-2xl p-5 overflow-hidden"
             style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:{{ $i*.08 }}s;transition:transform .3s,border-color .3s,box-shadow .3s"
             onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='{{ $glow }}';this.style.boxShadow='0 0 28px {{ $glow }},0 8px 24px rgba(0,0,0,.4)'"
             onmouseout="this.style.transform='';this.style.borderColor='rgba(255,255,255,.07)';this.style.boxShadow=''">
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full opacity-60"
                 style="background:radial-gradient(circle,{{ $glow }},transparent);filter:blur(24px)"></div>
            <div class="text-2xl mb-3">{{ $icon }}</div>
            <p class="text-[11px] text-slate-500 uppercase tracking-widest mb-1">{{ $label }}</p>
            <p class="font-playfair text-4xl" style="color:{{ $col }}">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    {{-- FORM TAMBAH BUKU --}}
    <div class="fade-up rounded-2xl p-6 mb-8" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);animation-delay:.15s">
        <h2 class="font-playfair text-xl text-cyan-400 mb-5">✦ Tambah Buku Baru</h2>
        <form action="/admin/books" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php $fields = [
                    ['judul',   'text',   'Judul Buku',   'Masukkan judul...', false],
                    ['penulis', 'text',   'Penulis',      'Nama penulis...',   false],
                    ['tahun',   'text',   'Tahun Terbit', 'Contoh: 2024',      false],
                    ['stock',   'number', 'Stock',        '',                  false],
                ]; @endphp

                @foreach($fields as [$name,$type,$lbl,$ph,$span])
                <div class="{{ $span ? 'md:col-span-2' : '' }}">
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">{{ $lbl }}</p>
                    <input type="{{ $type }}" name="{{ $name }}" placeholder="{{ $ph }}"
                           {{ $name=='stock' ? 'value=1 min=0' : '' }}
                           {{ in_array($name,['judul','penulis']) ? 'required' : '' }}
                           class="ocean-input">
                </div>
                @endforeach

                <div>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Kategori</p>
                    <select name="kategori_id" required class="ocean-input">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Cover Buku</p>
                    <input type="file" name="gambar" accept="image/*" class="ocean-input">
                </div>

                <div class="md:col-span-2">
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Sinopsis</p>
                    <textarea name="sinopsis" placeholder="Tulis sinopsis..." rows="3" class="ocean-input" style="resize:vertical"></textarea>
                </div>

                <div class="md:col-span-2">
                    <button type="submit"
                        class="w-full py-3 rounded-xl font-semibold text-sm tracking-wide"
                        style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18;transition:box-shadow .2s,filter .2s"
                        onmouseover="this.style.boxShadow='0 0 24px rgba(34,211,238,.45)';this.style.filter='brightness(1.08)'"
                        onmouseout="this.style.boxShadow='';this.style.filter=''">
                        ✦ Tambah Buku
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- DAFTAR BUKU --}}
    <div class="fade-up flex items-center gap-3 mb-5" style="animation-delay:.2s">
        <h2 class="font-playfair text-xl"
            style="background:linear-gradient(135deg,#fff,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
            Koleksi Buku
        </h2>
        <div class="flex-1 h-px" style="background:linear-gradient(90deg,rgba(34,211,238,.35),transparent)"></div>
        <span class="text-xs text-slate-500">{{ count($books) }} buku</span>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach($books as $i=>$book)
        <div class="fade-up flex flex-col rounded-2xl overflow-hidden"
             style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);animation-delay:{{ .25+$i*.04 }}s;transition:transform .3s,border-color .3s,box-shadow .3s"
             onmouseover="this.style.transform='translateY(-5px)';this.style.borderColor='rgba(34,211,238,.28)';this.style.boxShadow='0 0 20px rgba(34,211,238,.08),0 12px 28px rgba(0,0,0,.4)'"
             onmouseout="this.style.transform='';this.style.borderColor='rgba(255,255,255,.06)';this.style.boxShadow=''">

            <div class="h-40 flex items-center justify-center relative overflow-hidden"
                 style="border-bottom:1px solid rgba(255,255,255,.06)">
                <img src="{{ asset('images/'.$book->gambar) }}" alt="{{ $book->judul }}"
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
                    <a href="/books/{{ $book->id }}" target="_blank"
                       class="text-center py-1.5 rounded-lg text-[11px] font-medium text-slate-400 hover:text-slate-200 transition"
                       style="background:rgba(255,255,255,.05)">↗ Lihat</a>
                </div>
                <a href="/admin/books/delete/{{ $book->id }}"
                   class="block text-center mt-1.5 py-1.5 rounded-lg text-[11px] font-medium text-red-400 transition"
                   style="background:rgba(239,68,68,.08)"
                   onmouseover="this.style.background='rgba(239,68,68,.18)'"
                   onmouseout="this.style.background='rgba(239,68,68,.08)'"
                   onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑 Hapus</a>
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