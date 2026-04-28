@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500;600&display=swap');

    .font-cormorant { font-family: 'Cormorant Garamond', serif; }
    .font-dm        { font-family: 'DM Sans', sans-serif; }

    @keyframes bubbleRise {
        0%   { transform: translateY(0) translateX(0);    opacity: 0; }
        8%   { opacity: 1; }
        92%  { opacity: 0.5; }
        100% { transform: translateY(-110vh) translateX(18px); opacity: 0; }
    }
    .bubble {
        position: fixed; border-radius: 50%; pointer-events: none; z-index: 0;
        background: radial-gradient(circle at 30% 30%, rgba(0,212,255,0.35), rgba(0,168,232,0.04));
        border: 1px solid rgba(0,212,255,0.18);
        animation: bubbleRise linear infinite;
        bottom: -10px;
    }
    .bubble:nth-child(1)  { width:5px;  height:5px;  left:5%;  animation-duration:13s; animation-delay:0s;  }
    .bubble:nth-child(2)  { width:9px;  height:9px;  left:15%; animation-duration:18s; animation-delay:4s;  }
    .bubble:nth-child(3)  { width:4px;  height:4px;  left:30%; animation-duration:11s; animation-delay:7s;  }
    .bubble:nth-child(4)  { width:7px;  height:7px;  left:45%; animation-duration:15s; animation-delay:2s;  }
    .bubble:nth-child(5)  { width:5px;  height:5px;  left:60%; animation-duration:12s; animation-delay:9s;  }
    .bubble:nth-child(6)  { width:11px; height:11px; left:74%; animation-duration:20s; animation-delay:5s;  }
    .bubble:nth-child(7)  { width:3px;  height:3px;  left:86%; animation-duration:9s;  animation-delay:1s;  }
    .bubble:nth-child(8)  { width:6px;  height:6px;  left:52%; animation-duration:16s; animation-delay:11s; }
    .bubble:nth-child(9)  { width:8px;  height:8px;  left:22%; animation-duration:14s; animation-delay:6s;  }
    .bubble:nth-child(10) { width:4px;  height:4px;  left:68%; animation-duration:10s; animation-delay:3s;  }

    @keyframes pulse { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:0.5;transform:scale(1.3);} }
    .dot-pulse { animation: pulse 2s ease infinite; }

    @keyframes fadeDown { from{opacity:0;transform:translateY(-20px)} to{opacity:1;transform:translateY(0)} }
    @keyframes fadeUp   { from{opacity:0;transform:translateY(20px)}  to{opacity:1;transform:translateY(0)} }
    @keyframes cardRise { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }

    .anim-fade-down      { animation: fadeDown 0.8s ease both; }
    .anim-fade-down-1    { animation: fadeDown 0.8s 0.1s ease both; }
    .anim-fade-down-2    { animation: fadeDown 0.8s 0.2s ease both; }
    .anim-fade-down-3    { animation: fadeDown 0.8s 0.3s ease both; }
    .anim-fade-up-4      { animation: fadeUp   0.9s 0.4s ease both; }

    .book-card:nth-child(1) .card-inner { animation: cardRise 0.6s 0.05s ease both; }
    .book-card:nth-child(2) .card-inner { animation: cardRise 0.6s 0.10s ease both; }
    .book-card:nth-child(3) .card-inner { animation: cardRise 0.6s 0.15s ease both; }
    .book-card:nth-child(4) .card-inner { animation: cardRise 0.6s 0.20s ease both; }
    .book-card:nth-child(5) .card-inner { animation: cardRise 0.6s 0.25s ease both; }
    .book-card:nth-child(6) .card-inner { animation: cardRise 0.6s 0.30s ease both; }
    .book-card:nth-child(7) .card-inner { animation: cardRise 0.6s 0.35s ease both; }
    .book-card:nth-child(8) .card-inner { animation: cardRise 0.6s 0.40s ease both; }

    .cover-overlay {
        background: linear-gradient(180deg, transparent 50%, rgba(2,13,31,0.75) 100%);
    }

    .card-glow-line::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0,212,255,0.4), transparent);
        opacity: 0; transition: opacity 0.3s;
    }
    .card-glow-line:hover::before { opacity: 1; }

    .btn-detail-shimmer::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(0,212,255,0.18), transparent);
        opacity: 0; transition: opacity 0.25s;
    }
    .btn-detail-shimmer:hover::after { opacity: 1; }

    .cta-glow::before {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
        width: 600px; height: 600px; border-radius: 50%;
        background: radial-gradient(circle, rgba(0,168,232,0.07) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── RESPONSIVE FIXES ── */

    /* Stats bar: stack vertically di mobile */
    @media (max-width: 480px) {
        .stats-bar {
            flex-direction: column !important;
            border-radius: 18px !important;
        }
        .stats-bar .stat-item {
            border-right: none !important;
            border-bottom: 1px solid rgba(0,212,255,0.1);
        }
        .stats-bar .stat-item:last-child {
            border-bottom: none;
        }
    }

    /* Search input: pastikan tidak overflow */
    .search-input-wrapper input {
        min-width: 0;
    }
</style>




{{-- ROOT WRAPPER --}}
<div class="font-dm bg-[#020d1f] text-[#f0f8ff] overflow-x-hidden">

    {{-- FIXED BACKGROUND --}}
    <div class="fixed inset-0 z-0 pointer-events-none"
         style="background:
            radial-gradient(ellipse 70% 50% at 10% 5%, rgba(10,50,96,0.7) 0%, transparent 60%),
            radial-gradient(ellipse 50% 40% at 90% 15%, rgba(0,168,232,0.1) 0%, transparent 55%),
            linear-gradient(180deg, #020d1f 0%, #051d3a 40%, #0a3260 75%, #020d1f 100%);">
    </div>

    {{-- BUBBLES --}}
    <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
    <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
    <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
    <div class="bubble"></div>

    {{-- ══ HERO ══ --}}
    {{-- FIX: px-4 di mobile, px-8 di desktop; pt-20 di mobile, pt-28 di desktop --}}
    <section class="relative z-10 min-h-screen flex flex-col justify-center px-4 sm:px-8 pt-20 sm:pt-28 pb-16 sm:pb-24 overflow-hidden">

        {{-- Orbs --}}
        <div class="absolute -top-32 -right-24 w-[320px] h-[320px] sm:w-[520px] sm:h-[520px] rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(0,168,232,0.12) 0%, transparent 70%)"></div>
        <div class="absolute bottom-16 -left-20 w-[200px] h-[200px] sm:w-[340px] sm:h-[340px] rounded-full pointer-events-none"
             style="background: radial-gradient(circle, rgba(77,217,192,0.08) 0%, transparent 70%)"></div>

        <div class="max-w-[860px] mx-auto text-center relative z-10 w-full">

            {{-- Eyebrow --}}
            <div class="inline-flex items-center gap-2 sm:gap-2.5 bg-[rgba(0,212,255,0.08)] border border-[rgba(0,212,255,0.2)] rounded-full px-3 sm:px-[18px] py-1.5 text-[0.65rem] sm:text-[0.72rem] font-medium tracking-[0.2em] sm:tracking-[0.3em] uppercase text-[#00d4ff] mb-6 sm:mb-8 anim-fade-down">
                <span class="w-1.5 h-1.5 rounded-full bg-[#00d4ff] shadow-[0_0_8px_#00d4ff] dot-pulse shrink-0"></span>
                Perpustakaan Digital Indonesia
            </div>

            {{-- Title --}}
            {{-- clamp dimulai dari ukuran yang lebih kecil supaya tidak overflow di HP --}}
            <h1 class="font-cormorant text-[clamp(2.2rem,6vw,5.5rem)] font-light leading-[1.08] tracking-tight text-[#f0f8ff] mb-4 sm:mb-6 anim-fade-down-1">
                Jelajahi Lautan
                <span class="block font-semibold"><em class="italic text-[#00d4ff]">Pengetahuan</em> Tanpa Batas</span>
            </h1>

            {{-- Description --}}
            <p class="text-sm sm:text-base text-[rgba(240,248,255,0.45)] font-light max-w-[480px] mx-auto mb-8 sm:mb-11 leading-[1.8] anim-fade-down-2 px-2 sm:px-0">
                OceanLibrary hadir untuk memudahkan akses buku digital.
                Pinjam, baca, dan temukan koleksi terbaik untukmu — kapan saja, di mana saja.
            </p>

            {{-- Buttons --}}
            {{-- FIX: gap lebih kecil di mobile, tombol full-width di layar sangat kecil --}}
            <div class="flex gap-3 sm:gap-4 justify-center flex-wrap mb-10 sm:mb-16 anim-fade-down-3 px-2 sm:px-0">
                <a href="/books"
                   class="flex-1 sm:flex-none text-center bg-gradient-to-br from-[#00a8e8] to-[#0a7abf] text-white px-6 sm:px-8 py-3 rounded-xl text-sm font-semibold no-underline shadow-[0_8px_28px_rgba(0,168,232,0.35)] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_12px_36px_rgba(0,212,255,0.45)] hover:from-[#00d4ff] hover:to-[#0a8ad4]">
                    Jelajahi Koleksi
                </a>
                @auth
                    <a href="/peminjaman-saya"
                       class="flex-1 sm:flex-none text-center bg-white/5 border border-white/20 text-[#f0f8ff] px-6 sm:px-8 py-3 rounded-xl text-sm font-medium no-underline backdrop-blur-md transition-all duration-200 hover:bg-white/10 hover:border-white/35">
                        Peminjaman Saya
                    </a>
                @else
                    <a href="/register"
                       class="flex-1 sm:flex-none text-center bg-white/5 border border-white/20 text-[#f0f8ff] px-6 sm:px-8 py-3 rounded-xl text-sm font-medium no-underline backdrop-blur-md transition-all duration-200 hover:bg-white/10 hover:border-white/35">
                        Daftar Gratis
                    </a>
                @endauth
            </div>

            {{-- Stats --}}
            {{-- FIX: pakai class stats-bar + stat-item untuk responsive stacking via CSS media query --}}
            <div class="stats-bar flex max-w-[480px] mx-auto border border-[rgba(0,212,255,0.15)] rounded-[18px] bg-white/[0.03] backdrop-blur-xl overflow-hidden anim-fade-up-4">
                <div class="stat-item flex-1 py-4 sm:py-5 px-3 sm:px-4 text-center border-r border-[rgba(0,212,255,0.1)]">
                    <span class="font-cormorant text-[1.6rem] sm:text-[2rem] font-semibold text-[#00d4ff] leading-none block">{{ $totalBuku ?? '0' }}+</span>
                    <div class="text-[0.65rem] sm:text-[0.7rem] text-[rgba(240,248,255,0.45)] tracking-[0.08em] uppercase mt-1">Koleksi Buku</div>
                </div>
                <div class="stat-item flex-1 py-4 sm:py-5 px-3 sm:px-4 text-center border-r border-[rgba(0,212,255,0.1)]">
                    <span class="font-cormorant text-[1.6rem] sm:text-[2rem] font-semibold text-[#00d4ff] leading-none block">{{ $totalUser ?? '0' }}+</span>
                    <div class="text-[0.65rem] sm:text-[0.7rem] text-[rgba(240,248,255,0.45)] tracking-[0.08em] uppercase mt-1">Pengguna Aktif</div>
                </div>
                <div class="stat-item flex-1 py-4 sm:py-5 px-3 sm:px-4 text-center">
                    <span class="font-cormorant text-[1.6rem] sm:text-[2rem] font-semibold text-[#00d4ff] leading-none block">4.8★</span>
                    <div class="text-[0.65rem] sm:text-[0.7rem] text-[rgba(240,248,255,0.45)] tracking-[0.08em] uppercase mt-1">Rating</div>
                </div>
            </div>

        </div>
    </section>


    {{-- ══ SEARCH BAR ══ --}}
    {{-- FIX: dipindah ke luar section "Buku Terbaru" agar tidak nested secara aneh --}}
    <section class="relative z-10 px-4 sm:px-8 py-8">
        <div class="max-w-[620px] mx-auto">
            <form method="GET" action="/"
                  class="search-input-wrapper flex items-center gap-2 sm:gap-3 bg-white/[0.06] border border-[rgba(0,212,255,0.2)] rounded-2xl px-4 sm:px-5 py-3 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.3)] focus-within:border-[rgba(0,212,255,0.5)] focus-within:shadow-[0_8px_32px_rgba(0,212,255,0.1)] transition-all duration-300">

                <svg class="w-4 h-4 text-[#00d4ff] opacity-70 shrink-0" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul atau penulis..."
                    class="flex-1 bg-transparent border-none outline-none text-[#f0f8ff] text-sm font-light placeholder-[rgba(240,248,255,0.3)] caret-[#00d4ff] min-w-0"
                />

                @if(request('search'))
                <a href="/" class="text-[rgba(240,248,255,0.4)] hover:text-[#f0f8ff] transition-colors text-lg leading-none shrink-0">×</a>
                @endif

                <button type="submit"
                        class="bg-gradient-to-br from-[#00a8e8] to-[#0a7abf] text-white text-xs font-semibold px-3 sm:px-4 py-1.5 rounded-lg hover:from-[#00d4ff] hover:to-[#0a8ad4] transition-all duration-200 shrink-0 whitespace-nowrap">
                    Cari
                </button>
            </form>

            @if(request('search'))
            <p class="text-center text-[0.75rem] text-[rgba(240,248,255,0.4)] mt-3">
                Menampilkan hasil untuk
                <span class="text-[#00d4ff]">"{{ request('search') }}"</span>
                — {{ $books->count() }} buku ditemukan
            </p>
            @endif
        </div>
    </section>


    {{-- ══ BUKU TERBARU ══ --}}
    {{-- FIX: px-4 di mobile, px-8 di desktop --}}
    <section class="relative z-10 bg-white/[0.025] border-t border-[rgba(0,212,255,0.08)] py-12 sm:py-20 px-4 sm:px-8">

        {{-- FIX: flex-col di mobile, flex-row di sm ke atas --}}
        <div class="max-w-[1100px] mx-auto mb-8 sm:mb-10 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3 sm:gap-4">
            <div>
                <p class="text-[0.7rem] font-medium tracking-[0.3em] uppercase text-[#00d4ff] opacity-80 mb-1 flex items-center gap-2 before:content-[''] before:inline-block before:w-6 before:h-px before:bg-[#00d4ff] before:opacity-60">
                    Koleksi
                </p>
                <h2 class="font-cormorant text-[1.8rem] sm:text-[2.2rem] font-normal text-[#f0f8ff] m-0 leading-tight">Buku Terbaru</h2>
            </div>

            <a href="/books" class="text-[0.8rem] font-medium text-[#00d4ff] no-underline tracking-[0.05em] flex items-center gap-1 opacity-80 transition-opacity hover:opacity-100 after:content-['→'] self-start sm:self-auto">
                Lihat semua
            </a>
        </div>

        
        <div class="max-w-[1100px] mx-auto grid gap-4 sm:gap-5"
             style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr))">

            @foreach($books as $book)
            <div class="book-card">
                <div class="card-inner relative flex flex-col bg-gradient-to-b from-[rgba(15,76,138,0.4)] to-[rgba(5,29,58,0.7)] border border-[rgba(0,212,255,0.1)] rounded-2xl overflow-hidden backdrop-blur-lg transition-all duration-300 hover:-translate-y-2 hover:scale-[1.015] hover:shadow-[0_20px_60px_rgba(0,0,0,0.5),0_0_30px_rgba(0,212,255,0.1)] hover:border-[rgba(0,212,255,0.28)] card-glow-line">

                    {{-- Cover --}}
                    <div class="relative h-[175px] sm:h-[195px] bg-[rgba(2,13,31,0.5)] flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/'.$book->gambar) }}"
                             alt="{{ $book->judul }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="w-[80px] h-[115px] sm:w-[90px] sm:h-[130px] rounded-lg items-center justify-center font-cormorant text-[1.6rem] sm:text-[1.8rem] font-bold text-white tracking-tight"
                             style="background: linear-gradient(135deg, #{{ substr(md5($book->judul), 0, 6) }}, #0a3260); display:none;">
                            {{ strtoupper(substr($book->judul, 0, 2)) }}
                        </div>
                        <div class="cover-overlay absolute inset-0 pointer-events-none"></div>

                        <span class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 z-10 bg-[rgba(0,212,255,0.13)] border border-[rgba(0,212,255,0.3)] text-[#00d4ff] text-[0.58rem] sm:text-[0.62rem] font-medium tracking-[0.06em] uppercase px-2 py-0.5 rounded-full backdrop-blur-md">
                            {{ $book->kategori->nama ?? $book->kategori ?? 'Umum' }}
                        </span>

                        @auth
                        <a href="/favorite/{{ $book->id }}"
                           class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 z-10 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[rgba(2,13,31,0.6)] border border-white/15 flex items-center justify-center text-[11px] sm:text-[13px] no-underline backdrop-blur-md transition-transform hover:scale-110"
                           title="Favorit">
                            @if(auth()->user()->favorites && auth()->user()->favorites->contains('book_id', $book->id))
                                ❤️
                            @else
                                🤍
                            @endif
                        </a>
                        @endauth
                    </div>

                    {{-- Body --}}
                    <div class="p-3 sm:p-4 flex-1 flex flex-col gap-1">
                        <span class="text-[0.6rem] sm:text-[0.65rem] font-medium tracking-[0.1em] uppercase text-[#00d4ff] opacity-80">
                            {{ $book->kategori->nama ?? $book->kategori ?? 'Umum' }}
                        </span>
                        <h3 class="font-cormorant text-[0.95rem] sm:text-base font-semibold text-[#f0f8ff] leading-snug m-0 line-clamp-2">
                            {{ $book->judul }}
                        </h3>
                        <p class="text-[0.7rem] sm:text-[0.75rem] text-[rgba(240,248,255,0.45)] font-light line-clamp-1">{{ $book->penulis }}</p>

                        @php
                            $avg   = $book->ratings ? round($book->ratings->avg('nilai'), 1) : 0;
                            $count = $book->ratings ? $book->ratings->count() : 0;
                        @endphp
                        <div class="flex items-center gap-1 sm:gap-1.5 mt-0.5">
                            <span class="text-[#fbbf24] text-[0.65rem] sm:text-[0.7rem] tracking-wide">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= round($avg) ? '★' : '☆' }}
                                @endfor
                            </span>
                            <span class="text-[0.65rem] sm:text-[0.68rem] text-[rgba(240,248,255,0.45)]">({{ $count }})</span>
                        </div>

                        @if($book->tersedia)
                            <span class="inline-flex items-center gap-1 sm:gap-1.5 text-[0.63rem] sm:text-[0.68rem] font-medium px-2 py-0.5 rounded-full mt-1 w-fit bg-[rgba(46,204,113,0.1)] text-[#2ecc71] border border-[rgba(46,204,113,0.22)] before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-[#2ecc71] before:shadow-[0_0_5px_#2ecc71]">
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 sm:gap-1.5 text-[0.63rem] sm:text-[0.68rem] font-medium px-2 py-0.5 rounded-full mt-1 w-fit bg-[rgba(255,107,107,0.1)] text-[#ff6b6b] border border-[rgba(255,107,107,0.22)] before:content-[''] before:w-1.5 before:h-1.5 before:rounded-full before:bg-[#ff6b6b]">
                                Dipinjam
                            </span>
                        @endif

                        <a href="/books/{{ $book->id }}"
                           class="btn-detail-shimmer relative mt-auto text-center bg-gradient-to-br from-[rgba(0,168,232,0.18)] to-[rgba(0,168,232,0.08)] border border-[rgba(0,212,255,0.22)] text-[#00d4ff] text-[0.72rem] sm:text-[0.78rem] font-medium tracking-[0.05em] sm:tracking-[0.07em] uppercase py-2 rounded-lg no-underline transition-all duration-200 hover:border-[rgba(0,212,255,0.5)] hover:shadow-[0_0_20px_rgba(0,212,255,0.18)] hover:text-white overflow-hidden block mt-3">
                            Lihat Detail
                        </a>
                    </div>

                </div>
            </div>
            @endforeach

        </div>
    </section>

    {{-- ══ FITUR ══ --}}
    <section class="relative z-10 py-12 sm:py-20 px-4 sm:px-8 border-t border-[rgba(0,212,255,0.06)]">

        <div class="max-w-[1100px] mx-auto mb-0">
            <p class="text-[0.7rem] font-medium tracking-[0.3em] uppercase text-[#00d4ff] opacity-80 mb-1 flex items-center gap-2 before:content-[''] before:inline-block before:w-6 before:h-px before:bg-[#00d4ff] before:opacity-60">
                Keunggulan
            </p>
            <h2 class="font-cormorant text-[1.8rem] sm:text-[2.2rem] font-normal text-[#f0f8ff] m-0 leading-tight">Kenapa OceanLibrary?</h2>
        </div>

        <div class="max-w-[1100px] mx-auto mt-8 sm:mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">

            @foreach([
                ['icon' => '📚', 'title' => 'Koleksi Lengkap',  'desc' => 'Ratusan buku dari berbagai genre dan kategori tersedia untuk dipinjam kapan saja tanpa batas.'],
                ['icon' => '❤️', 'title' => 'Simpan Favorit',   'desc' => 'Tandai buku favoritmu dan akses kembali dengan mudah kapanpun kamu inginkan.'],
                ['icon' => '⭐', 'title' => 'Rating & Ulasan',  'desc' => 'Beri rating dan bantu pengguna lain menemukan buku terbaik dari koleksi kami.'],
            ] as $feat)
            <div class="relative overflow-hidden bg-gradient-to-b from-[rgba(15,76,138,0.35)] to-[rgba(5,29,58,0.6)] border border-[rgba(0,212,255,0.1)] rounded-[18px] p-6 sm:p-8 backdrop-blur-lg transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_16px_48px_rgba(0,0,0,0.35),0_0_24px_rgba(0,212,255,0.07)] hover:border-[rgba(0,212,255,0.22)] card-glow-line">
                <div class="w-[46px] h-[46px] sm:w-[52px] sm:h-[52px] rounded-[14px] flex items-center justify-center text-xl sm:text-2xl mb-4 sm:mb-5 bg-[rgba(0,212,255,0.08)] border border-[rgba(0,212,255,0.15)]">
                    {{ $feat['icon'] }}
                </div>
                <div class="font-cormorant text-[1.1rem] sm:text-[1.2rem] font-semibold text-[#f0f8ff] mb-2">{{ $feat['title'] }}</div>
                <p class="text-[0.82rem] sm:text-[0.85rem] text-[rgba(240,248,255,0.45)] font-light leading-[1.75] m-0">{{ $feat['desc'] }}</p>
            </div>
            @endforeach

        </div>
    </section>

    {{-- CTA --}}
    @guest
    <section class="cta-glow relative z-10 py-16 sm:py-24 px-4 sm:px-8 text-center border-t border-[rgba(0,212,255,0.08)] overflow-hidden">
        <p class="text-[0.7rem] tracking-[0.3em] uppercase text-[#00d4ff] opacity-75 mb-4">Bergabung Sekarang</p>
        <h2 class="font-cormorant text-[clamp(1.8rem,4vw,3.2rem)] font-light text-[#f0f8ff] mb-4 leading-[1.15]">
            Siap Mulai <em class="italic text-[#00d4ff]">Membaca?</em>
        </h2>
        <p class="text-[rgba(240,248,255,0.45)] text-[0.9rem] sm:text-[0.95rem] font-light mb-7 sm:mb-9 max-w-sm mx-auto">
            Daftar sekarang dan nikmati akses ke seluruh koleksi buku digital secara gratis.
        </p>
        <a href="/register"
           class="inline-block bg-gradient-to-br from-[#00a8e8] to-[#0a7abf] text-white px-8 py-3 rounded-xl text-sm font-semibold no-underline shadow-[0_8px_28px_rgba(0,168,232,0.35)] transition-all duration-200 hover:-translate-y-0.5 hover:shadow-[0_12px_36px_rgba(0,212,255,0.45)] hover:from-[#00d4ff] hover:to-[#0a8ad4]">
            Daftar Sekarang
        </a>
    </section>
    @endguest

</div>

@endsection     