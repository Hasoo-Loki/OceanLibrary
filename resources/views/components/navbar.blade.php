<nav style="background:#1A66CC; padding:15px 30px; color:white; display:flex; justify-content:space-between; align-items:center;">

    <div style="font-weight:bold; font-size:18px;">
        OceanLibrary
    </div>

    <div style="display:flex; gap:20px; align-items:center;">

        <a href="/" style="color:white;">Beranda</a>
        <a href="/books" style="color:white;">Koleksi</a>

        @auth
            <a href="/peminjaman-saya" style="color:white;">Riwayat</a>
            <a href="/denda-saya" style="color:white;">Denda</a>

            @if(auth()->user()->role == 'admin')
                <a href="/dashboard" style="color:#33CCFF;">Dashboard</a>
            @endif

            {{-- 🔔 BELL NOTIFIKASI USER --}}
            @php
                $notifUser = \App\Models\Notifikasi::where('user_id', auth()->id())
                    ->latest()->take(5)->get();
                $notifUserCount = \App\Models\Notifikasi::where('user_id', auth()->id())
                    ->where('dibaca', false)->count();
            @endphp

            <div style="position:relative;" x-data="{ openNotif: false }">

                <button @click="openNotif = !openNotif"
                    style="position:relative; background:none; border:none; cursor:pointer; padding:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($notifUserCount > 0)
                        <span style="
                            position:absolute; top:-4px; right:-4px;
                            background:red; color:white; font-size:10px; font-weight:bold;
                            width:16px; height:16px; border-radius:50%;
                            display:flex; align-items:center; justify-content:center;">
                            {{ $notifUserCount > 9 ? '9+' : $notifUserCount }}
                        </span>
                    @endif
                </button>

                {{-- DROPDOWN --}}
                <div x-show="openNotif" @click.away="openNotif = false"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="position:absolute; right:0; top:36px; width:300px; z-index:999;
                            background:#0e1e35; border-radius:12px;
                            border:1px solid rgba(34,211,238,.15);
                            box-shadow:0 0 30px rgba(34,211,238,.08); overflow:hidden;">

                    <div style="padding:12px 16px; display:flex; justify-content:space-between; align-items:center;
                                border-bottom:1px solid rgba(255,255,255,.06);">
                        <span style="font-size:13px; font-weight:600; color:#22d3ee;">🔔 Notifikasi</span>
                        @if($notifUserCount > 0)
                            <a href="/notifikasi/baca-semua"
                               style="font-size:11px; color:rgba(34,211,238,.7);"
                               @click="openNotif = false">
                                Tandai semua dibaca
                            </a>
                        @endif
                    </div>

                    <div style="max-height:240px; overflow-y:auto;">
                        @forelse($notifUser as $n)
                            <a href="/notifikasi/baca/{{ $n->id }}"
                               @click="openNotif = false"
                               style="display:block; padding:12px 16px;
                                      border-bottom:1px solid rgba(255,255,255,.04);
                                      background:{{ $n->dibaca ? 'transparent' : 'rgba(34,211,238,.04)' }};
                                      text-decoration:none; transition:background .2s;"
                               onmouseover="this.style.background='rgba(34,211,238,.08)'"
                               onmouseout="this.style.background='{{ $n->dibaca ? 'transparent' : 'rgba(34,211,238,.04)' }}'">
                                <p style="font-size:12px; color:#cbd5e1; line-height:1.5; margin:0;">
                                    {{ $n->pesan }}
                                </p>
                                <p style="font-size:10px; color:#475569; margin-top:4px;">
                                    {{ $n->created_at->diffForHumans() }}
                                </p>
                            </a>
                        @empty
                            <div style="padding:24px; text-align:center; font-size:13px; color:#475569;">
                                Belum ada notifikasi
                            </div>
                        @endforelse
                    </div>

                    <a href="/notifikasi"
                       @click="openNotif = false"
                       style="display:block; padding:10px; text-align:center;
                              font-size:12px; color:rgba(34,211,238,.7);
                              border-top:1px solid rgba(255,255,255,.06);
                              text-decoration:none; transition:color .2s;"
                       onmouseover="this.style.color='#22d3ee'"
                       onmouseout="this.style.color='rgba(34,211,238,.7)'">
                        Lihat semua notifikasi →
                    </a>
                </div>
            </div>

            <span>Halo, {{ auth()->user()->name }}</span>

            <form action="/logout" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:red; padding:5px 10px; border-radius:5px; color:white; border:none; cursor:pointer;">
                    Logout
                </button>
            </form>
        @endauth

        @guest
            <a href="/login" style="color:white;">Login</a>
        @endguest

    </div>
</nav>

{{-- Alpine.js untuk dropdown --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>