@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-b from-[#020d1a] via-[#03183a] to-[#020d1a] text-white overflow-x-hidden">

    {{-- ══════════════════════════════════ --}}
    {{--  HERO                             --}}
    {{-- ══════════════════════════════════ --}}
    <section class="relative flex flex-col items-center justify-center text-center px-6 pt-28 pb-20">

        {{-- Glow background --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-cyan-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-[400px] h-[300px] bg-blue-700/10 rounded-full blur-3xl"></div>
        </div>

        {{-- Badge --}}
        <div class="inline-flex items-center gap-2 border border-cyan-500/40 rounded-full px-5 py-2 text-[0.7rem] tracking-widest uppercase text-cyan-400 bg-cyan-500/5 mb-8">
            <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
            Perpustakaan Digital Indonesia
        </div>

        {{-- Title --}}
        <h1 class="font-serif text-4xl md:text-6xl font-bold leading-tight max-w-3xl mx-auto">
            Tentang
            <span class="italic text-cyan-400"> OceanLibrary</span>
        </h1>

        <p class="mt-6 max-w-xl text-slate-400 leading-relaxed text-base">
            Kami hadir untuk menghubungkan setiap pembaca dengan lautan pengetahuan tanpa batas — kapan saja, di mana saja.
        </p>

        {{-- Stats Strip --}}
        <div class="mt-14 w-full max-w-lg bg-white/5 border border-cyan-500/20 rounded-2xl flex divide-x divide-cyan-500/20">
            <div class="flex-1 py-5 flex flex-col items-center gap-1">
                <span class="font-serif text-3xl font-bold text-cyan-400">8+</span>
                <span class="text-[0.7rem] tracking-widest uppercase text-slate-400">Koleksi Buku</span>
            </div>
            <div class="flex-1 py-5 flex flex-col items-center gap-1">
                <span class="font-serif text-3xl font-bold text-cyan-400">7+</span>
                <span class="text-[0.7rem] tracking-widest uppercase text-slate-400">Pengguna Aktif</span>
            </div>
            <div class="flex-1 py-5 flex flex-col items-center gap-1">
                <span class="font-serif text-3xl font-bold text-cyan-400">4.8★</span>
                <span class="text-[0.7rem] tracking-widest uppercase text-slate-400">Rating</span>
            </div>
        </div>
    </section>

    {{-- Wave --}}
    <div class="-mt-2 opacity-10">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full block">
            <path d="M0 30C240 60 480 0 720 30C960 60 1200 0 1440 30V60H0V30Z" fill="#06b6d4"/>
        </svg>
    </div>

    {{-- ══════════════════════════════════ --}}
    {{--  STORY CARDS                      --}}
    {{-- ══════════════════════════════════ --}}
    <section class="px-6 py-20 max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-[0.72rem] tracking-[0.2em] uppercase text-cyan-400 mb-2">Cerita Kami</p>
            <h2 class="font-serif text-3xl md:text-4xl text-white">Dari Mana <span class="italic text-cyan-400">Kami</span> Bermula</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6">

            {{-- Card 1 --}}
            <div class="bg-white/[0.04] border border-cyan-500/15 rounded-2xl p-7 hover:-translate-y-2 hover:border-cyan-400/50 hover:shadow-[0_20px_60px_rgba(6,182,212,0.12)] transition-all duration-300">
                <div class="w-12 h-12 rounded-full bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center mb-5">
                    <svg class="text-cyan-400 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 3v2m0 14v2M3 12H1m22 0h-2M5.636 5.636l-1.414-1.414M19.778 19.778l-1.414-1.414M5.636 18.364l-1.414 1.414M19.778 4.222l-1.414 1.414"/>
                    </svg>
                </div>
                <h3 class="font-serif text-lg text-white mb-2">Lahir dari Mimpi</h3>
                <p class="text-slate-400 text-sm leading-7">OceanLibrary lahir dari visi sederhana: membuat buku berkualitas bisa diakses siapa saja tanpa hambatan fisik maupun biaya.</p>
            </div>

            {{-- Card 2 --}}
            <div class="bg-white/[0.04] border border-cyan-500/15 rounded-2xl p-7 hover:-translate-y-2 hover:border-cyan-400/50 hover:shadow-[0_20px_60px_rgba(6,182,212,0.12)] transition-all duration-300">
                <div class="w-12 h-12 rounded-full bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center mb-5">
                    <svg class="text-cyan-400 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <h3 class="font-serif text-lg text-white mb-2">Koleksi Terpilih</h3>
                <p class="text-slate-400 text-sm leading-7">Kami menghimpun koleksi buku digital yang dikurasi cermat — dari fiksi sastra hingga referensi ilmiah terkini.</p>
            </div>

            {{-- Card 3 --}}
            <div class="bg-white/[0.04] border border-cyan-500/15 rounded-2xl p-7 hover:-translate-y-2 hover:border-cyan-400/50 hover:shadow-[0_20px_60px_rgba(6,182,212,0.12)] transition-all duration-300">
                <div class="w-12 h-12 rounded-full bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center mb-5">
                    <svg class="text-cyan-400 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197"/>
                    </svg>
                </div>
                <h3 class="font-serif text-lg text-white mb-2">Komunitas Pembaca</h3>
                <p class="text-slate-400 text-sm leading-7">Lebih dari sekadar platform, kami membangun komunitas pembaca yang saling menginspirasi dan berbagi pengetahuan.</p>
            </div>

        </div>
    </section>

    {{-- ══════════════════════════════════ --}}
    {{--  VISI & MISI                      --}}
    {{-- ══════════════════════════════════ --}}
    <section class="px-6 py-20 max-w-5xl mx-auto">
        <div class="grid md:grid-cols-2 gap-10 items-start">

            {{-- VISI --}}
            <div class="relative bg-gradient-to-br from-blue-800/20 to-cyan-500/10 border border-cyan-500/25 rounded-3xl p-10 overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-14 -left-14 w-52 h-52 bg-blue-700/10 rounded-full blur-2xl pointer-events-none"></div>

                <p class="text-[0.72rem] tracking-[0.2em] uppercase text-cyan-400 mb-2">Visi</p>
                <h2 class="font-serif text-3xl text-white leading-snug mb-6">
                    Menjadi Perpustakaan<br>
                    <span class="italic text-cyan-400">Terbaik</span> di Indonesia
                </h2>
                <p class="text-slate-400 text-sm leading-7 mb-8">
                    Kami bercita-cita menjadi gerbang utama pengetahuan digital bagi seluruh masyarakat Indonesia — membangun ekosistem literasi yang inklusif, modern, dan berkelanjutan.
                </p>
                <div class="flex flex-col gap-3">
                    @foreach(['Inklusif untuk semua lapisan masyarakat', 'Teknologi modern yang terus berkembang', 'Berdampak positif bagi literasi nasional'] as $point)
                    <div class="flex items-center gap-2 text-cyan-400 text-sm">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        {{ $point }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- MISI --}}
            <div>
                <p class="text-[0.72rem] tracking-[0.2em] uppercase text-cyan-400 mb-2">Misi</p>
                <h2 class="font-serif text-3xl text-white leading-snug mb-8">
                    Langkah Nyata<br>
                    <span class="italic text-cyan-400">Kami</span>
                </h2>

                @php
                    $misi = [
                        'Menyediakan koleksi buku berkualitas tinggi yang terus diperbarui secara berkala.',
                        'Memudahkan akses membaca kapan saja dan di mana saja melalui platform digital.',
                        'Membangun sistem peminjaman yang transparan, adil, dan mudah digunakan.',
                        'Mendorong budaya membaca dan literasi digital di seluruh Indonesia.',
                    ];
                @endphp

                <div class="flex flex-col">
                    @foreach($misi as $i => $item)
                    <div class="flex items-start gap-4 py-4 border-b border-white/5 last:border-b-0 hover:pl-2 transition-all duration-300">
                        <span class="font-serif italic text-2xl text-cyan-400/50 leading-none min-w-[28px]">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="text-slate-400 text-sm leading-7 pt-0.5">{{ $item }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    {{-- ══════════════════════════════════ --}}
    {{--  TEAM                             --}}
    {{-- ══════════════════════════════════ --}}
    <section class="px-6 py-20 max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <p class="text-[0.72rem] tracking-[0.2em] uppercase text-cyan-400 mb-2">Tim Kami</p>
            <h2 class="font-serif text-3xl md:text-4xl text-white">Di Balik <span class="italic text-cyan-400">OceanLibrary</span></h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @php
                $team = [
                    ['A', 'Arif Santoso',   'Founder & CEO'],
                    ['D', 'Dewi Rahayu',    'Head of Content'],
                    ['R', 'Rizky Pratama',  'Lead Developer'],
                    ['S', 'Sari Wulandari', 'UX Designer'],
                ];
            @endphp

            @foreach($team as [$init, $name, $role])
            <div class="bg-white/[0.04] border border-cyan-500/15 rounded-2xl p-6 text-center hover:-translate-y-2 hover:border-cyan-400/40 transition-all duration-300">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-cyan-500 flex items-center justify-center mx-auto mb-4 border-2 border-cyan-400/30 font-serif text-2xl italic text-white">
                    {{ $init }}
                </div>
                <p class="font-semibold text-white text-sm">{{ $name }}</p>
                <p class="text-slate-400 text-xs mt-1">{{ $role }}</p>
            </div>
            @endforeach
        </div>
    </section>

   
    {{--cta--}}
    <section class="px-6 pb-28 max-w-3xl mx-auto">
        <div class="bg-gradient-to-br from-blue-800/20 to-cyan-500/10 border border-cyan-500/20 rounded-3xl p-12 md:p-16 text-center">
            <p class="text-[0.72rem] tracking-[0.2em] uppercase text-cyan-400 mb-2">Bergabung Sekarang</p>
            <h2 class="font-serif text-3xl md:text-4xl text-white mb-4">
                Siap <span class="italic text-cyan-400">Menjelajah</span> Bersama?
            </h2>
            <p class="text-slate-400 text-sm leading-7 mb-8 max-w-md mx-auto">
                Ribuan judul buku menunggu. Mulai perjalanan literasimu hari ini bersama OceanLibrary.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('books.index') }}"
                   class="bg-cyan-400 text-[#020d1a] font-semibold text-sm px-8 py-3.5 rounded-full hover:bg-cyan-300 hover:-translate-y-0.5 hover:shadow-[0_12px_32px_rgba(6,182,212,0.35)] transition-all duration-300">
                    Jelajahi Koleksi
                </a>
               
            </div>
        </div>
    </section>

</div>

@endsection