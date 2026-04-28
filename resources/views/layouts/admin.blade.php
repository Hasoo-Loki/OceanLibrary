<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ocean Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .logo-text { font-family: 'Playfair Display', serif; }
        .nav-link { transition: all .2s; }
        .nav-link:hover, .nav-link.active {
            background: rgba(34,211,238,.12);
            color: #22d3ee;
            border-left: 2px solid #22d3ee;
        }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: rgba(34,211,238,.3); border-radius: 4px; }
    </style>
</head>

<body class="bg-[#060d1a] text-slate-200 min-h-screen">
<div class="flex min-h-screen">

    <!-- ── SIDEBAR ── -->
    <aside class="w-56 min-h-screen flex flex-col fixed top-0 left-0 z-50"
           style="background:linear-gradient(180deg,#0b1a2e,#060d1a);border-right:1px solid rgba(255,255,255,.06)">

        <!-- Logo -->
        <div class="px-5 py-6" style="border-bottom:1px solid rgba(255,255,255,.06)">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-cyan-400/60 tracking-widest uppercase mb-0.5">Ocean</p>
                    <p class="logo-text text-lg text-white leading-tight">Library</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">Admin Panel</p>
                </div>

                {{-- Bell --}}
                @if(auth()->check() && auth()->user()->role == 'admin')
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="relative p-2 rounded-xl hover:bg-cyan-400/10 transition text-slate-400 hover:text-cyan-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if(isset($jml_notif_belum_dibaca) && $jml_notif_belum_dibaca > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-bold px-1 rounded-full animate-pulse">
                                {{ $jml_notif_belum_dibaca }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute left-full ml-3 top-0 w-72 rounded-2xl z-50 overflow-hidden"
                         style="background:#0e1e35;border:1px solid rgba(34,211,238,.15);box-shadow:0 0 30px rgba(34,211,238,.08)">

                        <div class="px-4 py-3 flex items-center justify-between"
                             style="border-bottom:1px solid rgba(255,255,255,.06)">
                            <span class="text-sm font-semibold text-cyan-400">Notifikasi</span>
                            @if(isset($jml_notif_belum_dibaca) && $jml_notif_belum_dibaca > 0)
                                <form action="{{ route('admin.notifikasi.baca-semua') }}" method="POST">
                                    @csrf
                                    <button class="text-[11px] text-cyan-400/70 hover:text-cyan-400">Tandai semua</button>
                                </form>
                            @endif
                        </div>

                        <div class="max-h-60 overflow-y-auto">
                            @forelse($notif_terbaru ?? [] as $n)
                                <a href="{{ route('admin.peminjaman') }}"
                                   class="block px-4 py-3 hover:bg-cyan-400/5 transition {{ $n->dibaca ? 'opacity-50' : '' }}"
                                   style="border-bottom:1px solid rgba(255,255,255,.04)">
                                    <p class="text-xs text-slate-300">{{ $n->pesan }}</p>
                                    <p class="text-[10px] text-slate-500 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                                </a>
                            @empty
                                <p class="text-center text-xs text-slate-500 py-6">Tidak ada notifikasi</p>
                            @endforelse
                        </div>

                        <div class="px-4 py-2 text-center" style="border-top:1px solid rgba(255,255,255,.06)">
                            <a href="{{ route('admin.peminjaman') }}" class="text-[11px] text-cyan-400/70 hover:text-cyan-400">Lihat semua →</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Nav -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 text-sm">
            @php
                $links = [
                    ['/dashboard',        '▪', 'Dashboard'],
                    ['/admin/books',       '▪', 'Buku'],
                    ['/admin/peminjaman',  '▪', 'Peminjaman'],
                     ['/admin/denda',     '▪', 'Denda'],
                    ['/admin/users',       '▪', 'User'],
                    ['/admin/kategori',    '▪', 'Kategori'],
                ];
            @endphp
            @foreach($links as [$href, $icon, $label])
                <a href="{{ $href }}"
                   class="nav-link flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-slate-400 text-sm border-l-2 border-transparent">
                    <span class="text-cyan-400/40 text-xs">{{ $icon }}</span>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <!-- Logout -->
        <div class="px-3 py-4" style="border-top:1px solid rgba(255,255,255,.06)">
            <form action="/logout" method="POST">
                @csrf
                <button class="w-full py-2 rounded-xl text-sm font-medium text-red-400 hover:bg-red-500/10 transition">
                    ← Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- ── CONTENT ── -->
    <main class="flex-1 ml-56 min-h-screen">
        @yield('content')
    </main>

</div>
</body>
</html>