<!DOCTYPE html>
<html lang="id" class="min-h-screen bg-[#0d2444] text-white font-sans flex flex-col">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OceanLibrary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { dark: '#0a0a0a' }
                }
            }
        }
    </script>
    <style>
        #fav-list::-webkit-scrollbar { width: 3px; }
        #fav-list::-webkit-scrollbar-track { background: transparent; }
        #fav-list::-webkit-scrollbar-thumb { background: rgba(0,212,255,0.3); border-radius: 99px; }

        #notif-list::-webkit-scrollbar { width: 3px; }
        #notif-list::-webkit-scrollbar-track { background: transparent; }
        #notif-list::-webkit-scrollbar-thumb { background: rgba(0,212,255,0.3); border-radius: 99px; }

        #fav-dropdown, #notif-dropdown {
            transform-origin: top right;
            transition: opacity 0.18s ease, transform 0.18s ease;
        }
        #fav-dropdown.fav-hidden, #notif-dropdown.notif-hidden {
            opacity: 0;
            transform: scale(0.95) translateY(-6px);
            pointer-events: none;
        }
        #fav-dropdown.fav-show, #notif-dropdown.notif-show {
            opacity: 1;
            transform: scale(1) translateY(0);
            pointer-events: auto;
        }
    </style>
</head>

<body class="min-h-screen bg-[#0d2444] text-white font-sans flex flex-col">

{{-- ══ NAVBAR ══ --}}
<nav class="flex justify-between items-center px-8 py-4 bg-white/5 backdrop-blur-md border-b border-white/10 sticky top-0 z-50">

    <a href="/" class="text-xl font-bold tracking-wide">
        <span class="text-cyan-400">Ocean</span>Library
    </a>

    <div class="flex gap-5 items-center">
        <a href="/" class="text-sm text-gray-300 hover:text-cyan-300 transition">Beranda</a>
        <a href="/books" class="text-sm text-gray-300 hover:text-cyan-300 transition">Koleksi</a>

        @auth
            <a href="/peminjaman-saya" class="text-sm text-gray-300 hover:text-cyan-300 transition">Peminjaman</a>
            <a href="/denda-saya" class="text-sm text-gray-300 hover:text-red-300 transition">Denda</a>

            @if(Auth::user()->role == 'admin')
                <a href="/admin/users" class="text-sm text-yellow-400 hover:text-yellow-300 transition">Kelola User</a>
                <a href="/dashboard" class="text-sm text-yellow-400 hover:text-yellow-300 transition">Dashboard</a>
            @endif

            {{-- ══ FAVORITES DROPDOWN ══ --}}
            <div class="relative" id="fav-wrapper">
                @php $favCount = Auth::user()->favorites()->count(); @endphp
                <button onclick="toggleFav()"
                    class="relative flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 hover:border-pink-500/50 hover:bg-pink-500/10 transition-all duration-200 text-sm text-gray-300 hover:text-pink-300 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span>Favorit</span>
                    @if($favCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-pink-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none shadow-[0_0_8px_rgba(236,72,153,0.6)]">
                            {{ $favCount > 9 ? '9+' : $favCount }}
                        </span>
                    @endif
                </button>

                <div id="fav-dropdown"
                     class="fav-hidden absolute right-0 mt-2.5 w-80 rounded-2xl border border-white/10 overflow-hidden shadow-[0_24px_64px_rgba(0,0,0,0.7)]"
                     style="background:rgba(5,20,45,0.97);backdrop-filter:blur(20px);">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            <span class="text-sm font-semibold text-white">Buku Favorit</span>
                        </div>
                        @if($favCount > 0)
                            <span class="text-xs text-pink-400 bg-pink-500/10 border border-pink-500/20 px-2 py-0.5 rounded-full">
                                {{ $favCount }} buku
                            </span>
                        @endif
                    </div>
                    <div id="fav-list" class="overflow-y-auto" style="max-height:320px;">
                        @php $favBooks = Auth::user()->favorites()->with('book')->get(); @endphp
                        @if($favBooks->isEmpty())
                            <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                                <p class="text-sm text-gray-400">Belum ada buku favorit</p>
                                <p class="text-xs text-gray-600 mt-1">Tekan ❤️ pada buku untuk menyimpannya</p>
                            </div>
                        @else
                            @foreach($favBooks as $fav)
                                @if($fav->book)
                                <a href="/books/{{ $fav->book->id }}" onclick="closeFav()"
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors duration-150 border-b border-white/5 last:border-b-0 group">
                                    <div class="w-10 h-14 rounded-lg overflow-hidden flex-shrink-0 bg-[rgba(2,13,31,0.8)]">
                                        <img src="{{ asset('images/'.$fav->book->gambar) }}" alt="{{ $fav->book->judul }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-white truncate group-hover:text-cyan-300 transition-colors">{{ $fav->book->judul }}</p>
                                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $fav->book->penulis }}</p>
                                        <span class="inline-block mt-1 text-[10px] text-cyan-400 bg-cyan-400/10 border border-cyan-400/20 px-1.5 py-0.5 rounded-full">
                                            {{ $fav->book->kategori ?? 'Umum' }}
                                        </span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600 group-hover:text-cyan-400 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    @if(!$favBooks->isEmpty())
                    <div class="px-4 py-3 border-t border-white/10 bg-white/[0.02]">
                        <a href="/books" onclick="closeFav()" class="block text-center text-xs text-cyan-400 hover:text-cyan-300 transition font-medium py-0.5">
                            Jelajahi lebih banyak buku →
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ══ BELL NOTIFIKASI ══ --}}
            @php
                $notifCount = \App\Models\Notifikasi::where('user_id', auth()->id())
                    ->where('dibaca', false)->count();
                $notifList = \App\Models\Notifikasi::where('user_id', auth()->id())
                    ->latest()->take(5)->get();
            @endphp

            <div class="relative" id="notif-wrapper">
                <button onclick="toggleNotif()"
                    class="relative flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 hover:border-cyan-500/50 hover:bg-cyan-500/10 transition-all duration-200 text-sm text-gray-300 hover:text-cyan-300 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span>Notifikasi</span>
                    @if($notifCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-cyan-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center leading-none shadow-[0_0_8px_rgba(34,211,238,0.6)] animate-pulse">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                    @endif
                </button>

                <div id="notif-dropdown"
                     class="notif-hidden absolute right-0 mt-2.5 w-80 rounded-2xl border border-white/10 overflow-hidden shadow-[0_24px_64px_rgba(0,0,0,0.7)]"
                     style="background:rgba(5,20,45,0.97);backdrop-filter:blur(20px);">

                    {{-- Header --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b border-white/10">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="text-sm font-semibold text-white">Notifikasi</span>
                        </div>
                        @if($notifCount > 0)
                            <a href="/notifikasi/baca-semua"
                               class="text-xs text-cyan-400 bg-cyan-500/10 border border-cyan-500/20 px-2 py-0.5 rounded-full hover:bg-cyan-500/20 transition">
                                Tandai semua dibaca
                            </a>
                        @endif
                    </div>

                    {{-- List --}}
                    <div id="notif-list" class="overflow-y-auto" style="max-height:320px;">
                        @if($notifList->isEmpty())
                            <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
                                <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-400">Belum ada notifikasi</p>
                                <p class="text-xs text-gray-600 mt-1">Notifikasi akan muncul di sini</p>
                            </div>
                        @else
                            @foreach($notifList as $n)
                            <a href="/notifikasi/baca/{{ $n->id }}" onclick="closeNotif()"
                               class="flex items-start gap-3 px-4 py-3 hover:bg-white/5 transition-colors duration-150 border-b border-white/5 last:border-b-0 group {{ $n->dibaca ? 'opacity-60' : '' }}">
                                {{-- Dot unread --}}
                                <div class="flex-shrink-0 mt-1">
                                    @if(!$n->dibaca)
                                        <span class="w-2 h-2 rounded-full bg-cyan-400 block shadow-[0_0_6px_rgba(34,211,238,0.8)]"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-white/10 block"></span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-300 leading-relaxed line-clamp-2 group-hover:text-white transition-colors">
                                        {{ $n->pesan }}
                                    </p>
                                    <p class="text-[10px] text-gray-600 mt-1">
                                        {{ $n->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="px-4 py-3 border-t border-white/10 bg-white/[0.02]">
                        <a href="/notifikasi" onclick="closeNotif()"
                           class="block text-center text-xs text-cyan-400 hover:text-cyan-300 transition font-medium py-0.5">
                            Lihat semua notifikasi →
                        </a>
                    </div>
                </div>
            </div>

            {{-- ══ USER INFO ══ --}}
            <div class="flex items-center gap-2.5 pl-3 border-l border-white/10">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-700 flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-[0_0_10px_rgba(0,212,255,0.35)]">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex flex-col leading-tight">
                    <span class="text-sm font-semibold text-white">{{ Auth::user()->name }}</span>
                    <span class="text-[11px] font-medium px-1.5 py-0.5 rounded-full w-fit mt-0.5
                        {{ Auth::user()->role == 'admin'
                            ? 'bg-yellow-500/15 text-yellow-400 border border-yellow-500/30'
                            : 'bg-cyan-500/15 text-cyan-400 border border-cyan-500/30' }}">
                        {{ ucfirst(Auth::user()->role ?? 'user') }}
                    </span>
                </div>
            </div>

            <form action="/logout" method="POST">
                @csrf
                <button class="bg-red-500/80 hover:bg-red-500 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-[0_0_12px_rgba(239,68,68,0.35)] cursor-pointer">
                    Logout
                </button>
            </form>

        @endauth

        @guest
            <a href="/login" class="text-sm text-gray-300 hover:text-cyan-300 transition">Login</a>
            <a href="/register" class="text-sm bg-cyan-500/20 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/30 px-3 py-1.5 rounded-lg transition">Daftar</a>
        @endguest
    </div>
</nav>

<main class="flex-1 ">
    @yield('content')
</main>

<footer class="bg-white/[0.03] border-t border-white/[0.08] text-white mt-auto relative z-50">
    <div class="max-w-6xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">
        <div>
            <h2 class="text-base font-semibold mb-3"><span class="text-cyan-400">Ocean</span>Library</h2>
            <p class="text-sm text-gray-400 leading-relaxed">
                OceanLibrary adalah platform perpustakaan digital yang menyediakan berbagai koleksi buku
                untuk mendukung pembelajaran dan literasi. Akses pengetahuan tanpa batas, seperti luasnya lautan.
            </p>
        </div>
        <div>
            <h2 class="text-base font-semibold mb-3 text-white">Navigasi</h2>
            <ul class="space-y-2 text-sm text-gray-400">
                <li><a href="/" class="hover:text-cyan-300 transition">Beranda</a></li>
                <li><a href="/books" class="hover:text-cyan-300 transition">Koleksi Buku</a></li>
                <li><a href="/peminjaman-saya" class="hover:text-cyan-300 transition">Peminjaman</a></li>
                <li><a href="/denda-saya" class="hover:text-red-300 transition">Denda</a></li>
                <li><a href="/about" class="hover:text-cyan-300 transition">Tentang</a></li>
                <li><a href="/contact" class="hover:text-cyan-300 transition">Kontak</a></li>
            </ul>
        </div>
        <div>
            <h2 class="text-base font-semibold mb-3 text-white">Kontak</h2>
            <p class="text-sm text-gray-400">📧 oceanlibrary@mail.com</p>
            <p class="text-sm text-gray-400 mt-1">📞 0812-3456-7890</p>
            <p class="text-sm text-gray-400 mt-1">🌏 Indonesia</p>
        </div>
    </div>
    <div class="text-center text-xs text-gray-600 py-4 border-t border-white/5">
        © {{ date('Y') }} OceanLibrary. All rights reserved.
    </div>
</footer>

<script>
    // ── FAVORIT ──
    function toggleFav() {
        const el = document.getElementById('fav-dropdown');
        el.classList.contains('fav-hidden') ? openFav() : closeFav();
        // Tutup notif kalau buka fav
        closeNotif();
    }
    function openFav() {
        document.getElementById('fav-dropdown').classList.remove('fav-hidden');
        document.getElementById('fav-dropdown').classList.add('fav-show');
    }
    function closeFav() {
        const el = document.getElementById('fav-dropdown');
        if (el) { el.classList.remove('fav-show'); el.classList.add('fav-hidden'); }
    }

    // ── NOTIFIKASI ──
    function toggleNotif() {
        const el = document.getElementById('notif-dropdown');
        el.classList.contains('notif-hidden') ? openNotif() : closeNotif();
        // Tutup fav kalau buka notif
        closeFav();
    }
    function openNotif() {
        document.getElementById('notif-dropdown').classList.remove('notif-hidden');
        document.getElementById('notif-dropdown').classList.add('notif-show');
    }
    function closeNotif() {
        const el = document.getElementById('notif-dropdown');
        if (el) { el.classList.remove('notif-show'); el.classList.add('notif-hidden'); }
    }

    // Klik di luar = tutup semua
    document.addEventListener('click', function(e) {
        if (!document.getElementById('fav-wrapper')?.contains(e.target)) closeFav();
        if (!document.getElementById('notif-wrapper')?.contains(e.target)) closeNotif();
    });
</script>

@stack('scripts')
</body>
</html>