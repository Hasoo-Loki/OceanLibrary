@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap');
    .font-cormorant { font-family: 'Cormorant Garamond', serif; }
    .cover-img { transition: transform .5s; }
    .cover-wrap:hover .cover-img { transform: scale(1.05); }
    .btn-hover { transition: box-shadow .25s, transform .25s; }
    .btn-hover:hover { box-shadow: 0 6px 28px rgba(0,212,255,.4); transform: translateY(-1px); }
    .rate-btn { transition: color .2s, transform .15s; }
    .rate-btn:hover { color: #fbbf24 !important; transform: scale(1.15); }
    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .fade-up { animation: fadeUp .6s ease both; }
</style>

<div class="min-h-screen text-slate-100 pb-12"
     style="font-family:'DM Sans',sans-serif;background:linear-gradient(160deg,#020d1f,#051d3a 60%,#0a3260)">

    <div class="max-w-4xl mx-auto px-6 pt-10">

        {{-- GRID UTAMA --}}
        <div class="grid md:grid-cols-[1fr_1.5fr] gap-8 items-start fade-up">

            {{-- COVER --}}
            <div class="cover-wrap relative rounded-2xl overflow-hidden"
                 style="aspect-ratio:2/3;border:1px solid rgba(0,212,255,.15);background:rgba(2,13,31,.5)">
                <img src="{{ asset('images/'.$book->gambar) }}" alt="{{ $book->judul }}"
                     class="cover-img w-full h-full object-cover">
                <div class="absolute inset-0 pointer-events-none"
                     style="background:linear-gradient(180deg,transparent 55%,rgba(2,13,31,.75) 100%)"></div>
                @if($book->kategori)
                <span class="absolute top-3 right-3 text-[10px] font-medium uppercase tracking-wider text-cyan-300 px-3 py-1 rounded-full"
                      style="background:rgba(0,212,255,.13);border:1px solid rgba(0,212,255,.3)">
                    {{ $book->kategori }}
                </span>
                @endif
            </div>

            {{-- INFO --}}
            <div>
                <p class="text-[11px] uppercase tracking-[.25em] text-cyan-400 mb-2" style="opacity:.7">Detail Buku</p>

                <h1 class="font-cormorant text-4xl font-light leading-tight text-slate-100 mb-1">
                    {{ $book->judul }}
                </h1>

                <div class="flex flex-col gap-1 mt-3 mb-4">
                    <span class="text-sm font-light" style="color:rgba(240,248,255,.5)">
                        <span style="color:rgba(240,248,255,.8)">Penulis:</span> {{ $book->penulis }}
                    </span>
                    <span class="text-sm font-light" style="color:rgba(240,248,255,.5)">
                        <span style="color:rgba(240,248,255,.8)">Tahun:</span> {{ $book->tahun }}
                    </span>
                    <span class="text-sm font-light" style="color:rgba(240,248,255,.5)">
                        <span style="color:rgba(240,248,255,.8)">Stok:</span> {{ $book->stock ?? 0 }} tersedia
                    </span>
                </div>

                {{-- STATUS BADGES --}}
                <div class="flex gap-2 flex-wrap mb-5">
                    @if($book->tersedia)
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-3 py-1 rounded-full text-emerald-400"
                              style="background:rgba(46,204,113,.1);border:1px solid rgba(46,204,113,.25)">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400" style="box-shadow:0 0 6px #2ecc71"></span>
                            Tersedia
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-3 py-1 rounded-full text-red-400"
                              style="background:rgba(255,107,107,.1);border:1px solid rgba(255,107,107,.25)">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            Tidak Tersedia
                        </span>
                    @endif
                    <span class="text-[11px] font-medium px-3 py-1 rounded-full text-cyan-300"
                          style="background:rgba(0,212,255,.1);border:1px solid rgba(0,212,255,.25)">
                        {{ $book->kategori ?? 'Umum' }}
                    </span>
                </div>

                {{-- ALERT --}}
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-xl text-sm text-emerald-300"
                         style="background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.2)">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 px-4 py-3 rounded-xl text-sm text-red-300"
                         style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.2)">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- TOMBOL --}}
                <div class="flex gap-3 flex-wrap">

                    @if(!auth()->check())
                        <a href="/login"
                           class="btn-hover inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white"
                           style="background:linear-gradient(135deg,#00a8e8,#0a7abf);box-shadow:0 4px 18px rgba(0,168,232,.3)">
                            Login untuk Meminjam
                        </a>

                    @elseif(!auth()->user()->is_member)
                        <div class="w-full">
                            <button disabled
                                class="w-full mb-2 px-5 py-2.5 rounded-xl text-sm font-medium cursor-not-allowed text-slate-500"
                                style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08)">
                                Pinjam Buku
                            </button>
                            <div class="px-4 py-3 rounded-xl text-sm text-amber-300"
                                 style="background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.2)">
                                Kamu belum menjadi <strong>Member</strong>. Hubungi admin untuk mengaktifkan membership.
                            </div>
                        </div>

                    @elseif(!$book->tersedia || $book->stock <= 0)
                        <button disabled
                            class="px-5 py-2.5 rounded-xl text-sm font-medium cursor-not-allowed text-slate-500"
                            style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08)">
                            Stok Habis
                        </button>

                    @else
                        <button type="button" onclick="openPinjamModal({{ $book->id }})"
                           class="btn-hover inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white"
                           style="background:linear-gradient(135deg,#00a8e8,#0a7abf);box-shadow:0 4px 18px rgba(0,168,232,.3)">
                            Pinjam Buku
                        </button>
                    @endif

                    @auth
                    @php $isFav = $book->favorites->where('user_id', auth()->id())->count(); @endphp
                    <a href="/favorite/{{ $book->id }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-pink-400"
                       style="{{ $isFav
                           ? 'background:rgba(236,72,153,.2);border:1px solid rgba(236,72,153,.5)'
                           : 'background:rgba(236,72,153,.1);border:1px solid rgba(236,72,153,.25)' }}">
                        {{ $isFav ? '❤️ Tersimpan' : '🤍 Simpan' }}
                    </a>
                    @endauth

                </div>
            </div>
        </div>

        {{-- DIVIDER --}}
        <div class="my-8 border-t" style="border-color:rgba(0,212,255,.08)"></div>

        {{-- SINOPSIS --}}
        <div class="fade-up rounded-2xl p-6 mb-5"
             style="background:rgba(255,255,255,.03);border:1px solid rgba(0,212,255,.1);animation-delay:.1s">
            <h2 class="font-cormorant text-xl font-normal text-slate-100 mb-3 flex items-center gap-3">
                Sinopsis
                <span class="flex-1 h-px" style="background:rgba(0,212,255,.1)"></span>
            </h2>
            <p class="text-sm font-light leading-relaxed" style="color:rgba(240,248,255,.55)">
                {{ $book->sinopsis ?? 'Tidak ada sinopsis.' }}
            </p>
        </div>

        {{-- RATING --}}
        <div class="fade-up rounded-2xl p-6 mb-5"
             style="background:rgba(255,255,255,.03);border:1px solid rgba(0,212,255,.1);animation-delay:.18s">
            <h2 class="font-cormorant text-xl font-normal text-slate-100 mb-4 flex items-center gap-3">
                Rating Buku
                <span class="flex-1 h-px" style="background:rgba(0,212,255,.1)"></span>
            </h2>

            <div class="flex items-center gap-1 mb-1">
                @for($i=1; $i<=5; $i++)
                    <span class="text-2xl {{ ($avg ?? 0) >= $i ? 'text-yellow-400' : 'text-slate-700' }}">★</span>
                @endfor
                <span class="text-sm ml-2" style="color:rgba(240,248,255,.4)">
                    {{ number_format($avg ?? 0, 1) }} / 5 ({{ $count ?? 0 }} user)
                </span>
            </div>

            @auth
            <p class="text-xs mt-3 mb-2" style="color:rgba(240,248,255,.35)">Beri penilaianmu:</p>
            <form action="/rating/{{ $book->id }}" method="POST">
                @csrf
                <div class="flex gap-1">
                    @for($i=1; $i<=5; $i++)
                        <button type="submit" name="nilai" value="{{ $i }}"
                            class="rate-btn text-3xl bg-transparent border-none cursor-pointer p-0"
                            style="color:rgba(240,248,255,.2)">★</button>
                    @endfor
                </div>
            </form>
            @else
                <p class="text-sm mt-2" style="color:rgba(240,248,255,.35)">Login untuk memberi rating.</p>
            @endauth
        </div>

    </div>
</div>

{{-- MODAL PILIH DURASI PEMINJAMAN --}}
<div id="pinjamModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,.6);z-index:9999;align-items:center;justify-content:center"
     onclick="if(event.target===this) closePinjamModal()">
    <div class="bg-slate-900 rounded-2xl shadow-2xl max-w-md w-full mx-4"
         style="border:1px solid rgba(0,212,255,.2)">
        
        {{-- HEADER --}}
        <div class="px-6 py-4 border-b" style="border-color:rgba(0,212,255,.1)">
            <h2 class="text-lg font-semibold text-slate-100">Pilih Durasi Peminjaman</h2>
            <p class="text-sm text-slate-400 mt-1">Berapa lama kamu ingin meminjam buku ini?</p>
        </div>

        {{-- FORM --}}
        <form method="POST" action="/pinjam" class="px-6 py-5">
            @csrf
            <input type="hidden" name="book_id" id="modalBookId">

            <div class="space-y-3 mb-6">
                <label class="flex items-center p-4 rounded-xl cursor-pointer transition"
                       style="background:rgba(0,212,255,.08);border:2px solid rgba(0,212,255,.2)">
                    <input type="radio" name="durasi" value="1" required class="mr-3">
                    <div>
                        <p class="font-medium text-slate-100">1 Hari</p>
                        <p class="text-sm text-slate-400">Peminjaman singkat</p>
                    </div>
                </label>

                <label class="flex items-center p-4 rounded-xl cursor-pointer transition"
                       style="background:rgba(0,212,255,.08);border:2px solid rgba(0,212,255,.15)">
                    <input type="radio" name="durasi" value="3" required class="mr-3">
                    <div>
                        <p class="font-medium text-slate-100">3 Hari</p>
                        <p class="text-sm text-slate-400">Opsi umum</p>
                    </div>
                </label>

                <label class="flex items-center p-4 rounded-xl cursor-pointer transition"
                       style="background:rgba(0,212,255,.08);border:2px solid rgba(0,212,255,.15)">
                    <input type="radio" name="durasi" value="7" checked required class="mr-3">
                    <div>
                        <p class="font-medium text-slate-100">7 Hari</p>
                        <p class="text-sm text-slate-400">Maksimal (standar)</p>
                    </div>
                </label>
            </div>

            {{-- BUTTONS --}}
            <div class="flex gap-3">
                <button type="button" onclick="closePinjamModal()"
                        class="flex-1 px-4 py-2 rounded-xl text-sm font-medium text-slate-300 transition"
                        style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1)"
                        onmouseover="this.style.background='rgba(255,255,255,.1)'"
                        onmouseout="this.style.background='rgba(255,255,255,.07)'">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 rounded-xl text-sm font-medium text-white transition"
                        style="background:linear-gradient(135deg,#00a8e8,#0a7abf)"
                        onmouseover="this.style.boxShadow='0 0 18px rgba(0,168,232,.4)'"
                        onmouseout="this.style.boxShadow=''">
                    Kirim Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPinjamModal(bookId) {
    document.getElementById('modalBookId').value = bookId;
    const modal = document.getElementById('pinjamModal');
    modal.style.display = 'flex';
}

function closePinjamModal() {
    document.getElementById('pinjamModal').style.display = 'none';
}

// Close modal when pressing Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePinjamModal();
    }
});
</script>

@endsection