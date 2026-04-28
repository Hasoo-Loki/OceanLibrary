@extends('layouts.app')

@section('content')

<div class="bg-[#020d1a] min-h-screen text-[#e0eef8] font-sans">

    {{-- HERO --}}
    <section class="relative px-6 pt-20 pb-16 text-center overflow-hidden"
        style="background: linear-gradient(180deg, #020d1a 0%, #04203a 100%);">

        <div class="absolute inset-0 pointer-events-none"
            style="background: radial-gradient(ellipse 80% 60% at 50% 120%, rgba(0,180,160,0.12) 0%, transparent 70%), radial-gradient(ellipse 60% 40% at 20% 80%, rgba(0,120,200,0.10) 0%, transparent 60%);">
        </div>

        <div class="relative z-10 max-w-xl mx-auto">
            <span class="inline-block text-xs font-medium tracking-widest text-[#22d3c8] border border-[#22d3c8]/30 bg-[#22d3c8]/10 px-4 py-1.5 rounded-full mb-5">
                📍 Temukan Kami
            </span>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4 text-[#e0eef8]">
                Hubungi <span class="text-[#22d3c8] italic">OceanLibrary</span>
            </h1>
            <p class="text-[#7fb3cc] text-base">
                Kami siap membantu perjalanan literasimu. Jangan ragu untuk menghubungi kami.
            </p>
        </div>
    </section>

    {{-- BODY --}}
    <section class="max-w-6xl mx-auto px-6 pb-20 pt-10">
        <div class="grid grid-cols-1 md:grid-cols-[340px_1fr] gap-7 items-start">

            {{-- INFO CARDS --}}
            <div class="flex flex-col gap-4">

                {{-- Alamat --}}
                <div class="flex items-start gap-4 bg-white/[0.04] border border-[#22d3c8]/20 rounded-2xl p-5 hover:bg-[#22d3c8]/[0.07] hover:border-[#22d3c8]/40 transition-all">
                    <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-[#22d3c8]/10 border border-[#22d3c8]/25 rounded-xl text-[#22d3c8]">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-[#22d3c8] uppercase tracking-widest mb-1">Alamat</h3>
                        <p class="text-sm text-[#9abccc] leading-relaxed">
                            Jl. Letjen Ibrahim Adjie No.178<br>
                            Sindangbarang, Bogor Barat<br>
                            Kota Bogor, Jawa Barat 16117
                        </p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex items-start gap-4 bg-white/[0.04] border border-[#22d3c8]/20 rounded-2xl p-5 hover:bg-[#22d3c8]/[0.07] hover:border-[#22d3c8]/40 transition-all">
                    <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-[#22d3c8]/10 border border-[#22d3c8]/25 rounded-xl text-[#22d3c8]">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-[#22d3c8] uppercase tracking-widest mb-1">Email</h3>
                        <a href="mailto:oceanlibrary@gmail.com" class="text-sm text-[#7fb3cc] hover:text-[#22d3c8] transition-colors">
                            oceanlibrary@gmail.com
                        </a>
                    </div>
                </div>

                {{-- Telepon --}}
                <div class="flex items-start gap-4 bg-white/[0.04] border border-[#22d3c8]/20 rounded-2xl p-5 hover:bg-[#22d3c8]/[0.07] hover:border-[#22d3c8]/40 transition-all">
                    <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-[#22d3c8]/10 border border-[#22d3c8]/25 rounded-xl text-[#22d3c8]">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-[#22d3c8] uppercase tracking-widest mb-1">Telepon</h3>
                        <a href="tel:+6225118348108" class="text-sm text-[#7fb3cc] hover:text-[#22d3c8] transition-colors">
                            +62 251 8348108
                        </a>
                    </div>
                </div>

                {{-- Jam Operasional --}}
                <div class="flex items-start gap-4 bg-white/[0.04] border border-[#22d3c8]/20 rounded-2xl p-5 hover:bg-[#22d3c8]/[0.07] hover:border-[#22d3c8]/40 transition-all">
                    <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center bg-[#22d3c8]/10 border border-[#22d3c8]/25 rounded-xl text-[#22d3c8]">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-[#22d3c8] uppercase tracking-widest mb-1">Jam Operasional</h3>
                        <p class="text-sm text-[#9abccc] leading-relaxed">
                            Senin – Jumat: 07.00 – 17.00<br>
                            Sabtu: 07.00 – 12.30<br>
                            Minggu: Tutup
                        </p>
                    </div>
                </div>

                {{-- SMK Card --}}
                <div class="flex items-start gap-4 border border-[#0078c8]/30 rounded-2xl p-5"
                    style="background: linear-gradient(135deg, rgba(0,100,180,0.12) 0%, rgba(0,180,160,0.08) 100%);">
                    <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-xl text-white text-[11px] font-black tracking-wide"
                        style="background: linear-gradient(135deg, #0060b0, #00a89a);">
                        SMK
                    </div>
                    <div>
                        <h3 class="text-xs font-semibold text-[#22d3c8] uppercase tracking-widest mb-1">SMK Infokom Kota Bogor</h3>
                        <p class="text-sm text-[#9abccc] leading-relaxed">
                            Sekolah Menengah Kejuruan berbasis teknologi informasi & komunikasi di jantung Kota Bogor, Jawa Barat.
                        </p>
                    </div>
                </div>

            </div>

            {{-- MAP + FORM --}}
            <div class="flex flex-col gap-6">

                {{-- Map --}}
                <div class="border border-[#22d3c8]/20 rounded-2xl overflow-hidden bg-white/[0.04] flex flex-col" style="height: 340px;">
                    <div class="flex items-center gap-2 px-4 py-3 bg-[#22d3c8]/[0.08] border-b border-[#22d3c8]/20 text-[#22d3c8] text-sm font-medium flex-shrink-0">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6-3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        Lokasi Kami di Google Maps
                    </div>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.2!2d106.76722699999999!3d-6.5806046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c4f906e40827%3A0x6b93a49a2674507c!2sSMK%20Infokom%20Kota%20Bogor!5e0!3m2!1sid!2sid!4v1"
                        width="100%" height="100%"
                        class="flex-1"
                        style="border:0; filter: hue-rotate(195deg) saturate(0.6) brightness(0.7);"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

               

            </div>
        </div>
    </section>

</div>

@endsection