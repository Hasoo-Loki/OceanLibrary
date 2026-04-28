<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — OceanLibrary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        .ocean-input {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 10px;
            padding: .75rem 1rem;
            color: #e2e8f0;
            font-size: .875rem;
            width: 100%;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .ocean-input::placeholder { color: #475569; }
        .ocean-input:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 0 3px rgba(34,211,238,.1);
        }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp .6s ease both; }
        .particle {
            position: fixed; border-radius: 50%; background: #22d3ee;
            pointer-events: none; z-index: 0; opacity: 0;
            animation: floatUp var(--d) var(--delay) infinite linear;
        }
        @keyframes floatUp {
            0%   { opacity:0; transform:translateY(0); }
            10%  { opacity:.4; }
            90%  { opacity:.2; }
            100% { opacity:0; transform:translateY(-100vh); }
        }
    </style>
</head>

<body style="background:linear-gradient(160deg,#060d1a,#0a1628 60%,#071220); min-height:100vh; display:flex; align-items:center; justify-content:center;">

    {{-- Ambient blobs --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute w-[500px] h-[500px] rounded-full -top-40 -left-32"
             style="background:radial-gradient(circle,rgba(34,211,238,.06),transparent)"></div>
        <div class="absolute w-[400px] h-[400px] rounded-full -bottom-32 -right-20"
             style="background:radial-gradient(circle,rgba(56,189,248,.05),transparent)"></div>
        <div class="absolute w-[300px] h-[300px] rounded-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
             style="background:radial-gradient(circle,rgba(34,211,238,.03),transparent)"></div>
    </div>

    <div id="ptc" class="fixed inset-0 z-0 pointer-events-none"></div>

    {{-- CARD --}}
    <div class="relative z-10 w-full max-w-md mx-4 fade-up">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-1.5 text-[10px] tracking-widest uppercase text-cyan-400 border border-cyan-400/25 bg-cyan-400/10 rounded-full px-3 py-1 mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                Perpustakaan Digital Indonesia
            </span>
            <h1 class="font-playfair text-4xl mt-2"
                style="background:linear-gradient(135deg,#fff 30%,#22d3ee);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                OceanLibrary
            </h1>
            <p class="text-slate-500 text-sm mt-1">Jelajahi lautan pengetahuan tanpa batas</p>
        </div>

        {{-- Form Card --}}
        <div class="rounded-2xl p-8" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);backdrop-filter:blur(12px)">

            <h2 class="font-playfair text-xl text-cyan-400 mb-6">✦ Masuk ke Akun</h2>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-xl text-sm text-emerald-300 border border-emerald-400/20"
                     style="background:rgba(52,211,153,.08)">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 px-4 py-3 rounded-xl text-sm text-red-300 border border-red-400/20"
                     style="background:rgba(239,68,68,.08)">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-4">
                @csrf

                <div>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Email</p>
                    <input type="email" name="email" placeholder="contoh@email.com"
                           value="{{ old('email') }}"
                           class="ocean-input">
                </div>

                <div>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider mb-1.5">Password</p>
                    <input type="password" name="password" placeholder="••••••••"
                           class="ocean-input">
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold text-sm tracking-wide mt-2"
                    style="background:linear-gradient(135deg,#0ea5e9,#22d3ee);color:#020c18;transition:box-shadow .2s,filter .2s"
                    onmouseover="this.style.boxShadow='0 0 24px rgba(34,211,238,.45)';this.style.filter='brightness(1.08)'"
                    onmouseout="this.style.boxShadow='';this.style.filter=''">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Belum punya akun?
                <a href="/register" class="text-cyan-400 hover:text-cyan-300 transition font-medium">
                    Daftar di sini
                </a>
            </p>
        </div>

        <p class="text-center text-xs text-slate-700 mt-6">
            © 2026 OceanLibrary — Perpustakaan Digital Indonesia
        </p>
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

</body>
</html>