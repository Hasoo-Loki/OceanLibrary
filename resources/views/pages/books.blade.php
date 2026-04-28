@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap');
    :root {
        --biolum: #00d4ff; --pearl: #f0f8ff;
        --coral: #ff6b6b; --kelp: #2ecc71;
    }
    .ocean-page { font-family:'DM Sans',sans-serif; background:#020d1f; position:relative; overflow-x:hidden; color:var(--pearl); padding-bottom:2rem; }
    .ocean-bg { position:fixed; inset:0; z-index:0; pointer-events:none; }
    .ocean-bg::before { content:''; position:absolute; inset:0; background: radial-gradient(ellipse 80% 60% at 20% 0%,rgba(10,50,96,.8) 0%,transparent 60%), radial-gradient(ellipse 60% 50% at 80% 10%,rgba(0,168,232,.12) 0%,transparent 50%), linear-gradient(180deg,#020d1f 0%,#051d3a 40%,#0a3260 75%,#020d1f 100%); }
    .bubble { position:absolute; border-radius:50%; background:radial-gradient(circle at 30% 30%,rgba(0,212,255,.4),rgba(0,168,232,.05)); border:1px solid rgba(0,212,255,.2); animation:rise linear infinite; }
    .bubble:nth-child(1){width:6px;height:6px;left:8%;bottom:-20px;animation-duration:12s;animation-delay:0s}
    .bubble:nth-child(2){width:10px;height:10px;left:18%;bottom:-20px;animation-duration:17s;animation-delay:3s}
    .bubble:nth-child(3){width:4px;height:4px;left:32%;bottom:-20px;animation-duration:10s;animation-delay:6s}
    .bubble:nth-child(4){width:8px;height:8px;left:48%;bottom:-20px;animation-duration:14s;animation-delay:1s}
    .bubble:nth-child(5){width:5px;height:5px;left:62%;bottom:-20px;animation-duration:11s;animation-delay:8s}
    .bubble:nth-child(6){width:12px;height:12px;left:75%;bottom:-20px;animation-duration:19s;animation-delay:4s}
    .bubble:nth-child(7){width:3px;height:3px;left:88%;bottom:-20px;animation-duration:9s;animation-delay:2s}
    .bubble:nth-child(8){width:7px;height:7px;left:55%;bottom:-20px;animation-duration:16s;animation-delay:10s}
    @keyframes rise { 0%{transform:translateY(0);opacity:0} 10%{opacity:1} 90%{opacity:.6} 100%{transform:translateY(-110vh) translateX(20px);opacity:0} }
    .ocean-content { position:relative; z-index:10; max-width:1200px; margin:0 auto; padding:3rem 1.5rem 1rem; }
    .page-header { text-align:center; margin-bottom:3.5rem; animation:fadeDown .8s ease both; }
    .page-header .eyebrow { font-size:.72rem; font-weight:500; letter-spacing:.3em; text-transform:uppercase; color:var(--biolum); opacity:.8; margin-bottom:.75rem; display:flex; align-items:center; justify-content:center; gap:.75rem; }
    .page-header .eyebrow::before,.page-header .eyebrow::after { content:''; display:inline-block; width:40px; height:1px; background:var(--biolum); opacity:.5; }
    .page-header h1 { font-family:'Cormorant Garamond',serif; font-size:clamp(2.8rem,5vw,4.2rem); font-weight:300; line-height:1.1; color:var(--pearl); margin:0; }
    .page-header h1 em { font-style:italic; color:var(--biolum); }
    .page-header .subtitle { font-size:.9rem; color:rgba(240,248,255,.45); margin-top:.75rem; font-weight:300; }
    @keyframes fadeDown { from{opacity:0;transform:translateY(-24px)} to{opacity:1;transform:translateY(0)} }
    .search-bar { display:flex; gap:.75rem; max-width:700px; margin:0 auto 3.5rem; animation:fadeUp .9s .15s ease both; background:rgba(255,255,255,.04); border:1px solid rgba(0,212,255,.18); border-radius:14px; padding:.55rem .55rem .55rem 1.25rem; transition:box-shadow .3s,border-color .3s; }
    .search-bar:focus-within { border-color:rgba(0,212,255,.45); box-shadow:0 0 60px rgba(0,212,255,.12); }
    .search-bar input[type="text"] { flex:1; background:transparent; border:none; outline:none; color:var(--pearl); font-size:.92rem; font-weight:300; }
    .search-bar input::placeholder { color:rgba(240,248,255,.35); }
    .search-bar select { background:rgba(0,212,255,.08); border:1px solid rgba(0,212,255,.2); border-radius:8px; color:var(--pearl); font-size:.82rem; padding:.4rem .75rem; outline:none; cursor:pointer; }
    .search-bar select option { background:#051d3a; }
    .search-bar .btn-search { background:linear-gradient(135deg,#00a8e8,#0a7abf); border:none; border-radius:10px; color:#fff; font-size:.85rem; font-weight:500; padding:.5rem 1.4rem; cursor:pointer; display:flex; align-items:center; gap:.4rem; transition:all .25s; white-space:nowrap; }
    .search-bar .btn-search:hover { background:linear-gradient(135deg,#00d4ff,#0a8ad4); transform:translateY(-1px); }
    @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .book-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:1.6rem; }
    .book-card { background:linear-gradient(160deg,rgba(15,76,138,.4) 0%,rgba(5,29,58,.7) 100%); border:1px solid rgba(0,212,255,.12); border-radius:16px; overflow:hidden; display:flex; flex-direction:column; transition:transform .35s cubic-bezier(.22,1,.36,1),box-shadow .35s,border-color .3s; animation:cardRise .6s ease both; position:relative; }
    .book-card::before { content:''; position:absolute; top:0; left:0; right:0; height:1px; background:linear-gradient(90deg,transparent,rgba(0,212,255,.4),transparent); opacity:0; transition:opacity .3s; }
    .book-card:hover::before { opacity:1; }
    .book-card:hover { transform:translateY(-8px) scale(1.015); box-shadow:0 20px 60px rgba(0,0,0,.5),0 0 30px rgba(0,212,255,.1); border-color:rgba(0,212,255,.3); }
    @keyframes cardRise { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
    .book-card:nth-child(1){animation-delay:.05s}.book-card:nth-child(2){animation-delay:.10s}.book-card:nth-child(3){animation-delay:.15s}.book-card:nth-child(4){animation-delay:.20s}.book-card:nth-child(5){animation-delay:.25s}.book-card:nth-child(6){animation-delay:.30s}.book-card:nth-child(7){animation-delay:.35s}.book-card:nth-child(8){animation-delay:.40s}
    .book-cover { height:200px; background:rgba(2,13,31,.5); overflow:hidden; position:relative; }
    .book-cover img { width:100%; height:100%; object-fit:cover; transition:transform .5s cubic-bezier(.22,1,.36,1); }
    .book-card:hover .book-cover img { transform:scale(1.08); }
    .book-cover::after { content:''; position:absolute; inset:0; background:linear-gradient(180deg,transparent 50%,rgba(2,13,31,.7) 100%); pointer-events:none; }
    .cover-badge { position:absolute; top:10px; right:10px; background:rgba(0,212,255,.15); border:1px solid rgba(0,212,255,.3); color:var(--biolum); font-size:.65rem; font-weight:500; letter-spacing:.08em; text-transform:uppercase; padding:3px 9px; border-radius:20px; z-index:1; }
    .book-body { padding:1rem 1.1rem 1.1rem; flex:1; display:flex; flex-direction:column; gap:.2rem; }
    .book-title { font-family:'Cormorant Garamond',serif; font-size:1.05rem; font-weight:600; color:var(--pearl); line-height:1.3; margin:0; }
    .book-author { font-size:.78rem; color:rgba(240,248,255,.45); font-weight:300; }
    .status-badge { display:inline-flex; align-items:center; gap:5px; font-size:.72rem; font-weight:500; padding:3px 9px; border-radius:20px; margin-top:.35rem; width:fit-content; }
    .status-badge::before { content:''; width:6px; height:6px; border-radius:50%; display:inline-block; }
    .status-tersedia { background:rgba(46,204,113,.12); color:var(--kelp); border:1px solid rgba(46,204,113,.25); }
    .status-tersedia::before { background:var(--kelp); box-shadow:0 0 6px var(--kelp); }
    .status-dipinjam { background:rgba(255,107,107,.12); color:var(--coral); border:1px solid rgba(255,107,107,.25); }
    .status-dipinjam::before { background:var(--coral); }
    .btn-detail { display:block; margin-top:auto; text-align:center; background:linear-gradient(135deg,rgba(0,168,232,.18),rgba(0,168,232,.08)); border:1px solid rgba(0,212,255,.25); color:var(--biolum); font-size:.82rem; font-weight:500; letter-spacing:.06em; text-transform:uppercase; padding:.6rem; border-radius:8px; text-decoration:none; transition:all .25s; position:relative; overflow:hidden; }
    .btn-detail::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(0,212,255,.2),transparent); opacity:0; transition:opacity .25s; }
    .btn-detail:hover::after { opacity:1; }
    .btn-detail:hover { border-color:rgba(0,212,255,.5); box-shadow:0 0 20px rgba(0,212,255,.2); color:#fff; }
    .empty-state { text-align:center; padding:5rem 1rem; color:rgba(240,248,255,.35); }
    .empty-state .icon { font-size:3.5rem; margin-bottom:1rem; opacity:.4; }
    .empty-state p { font-family:'Cormorant Garamond',serif; font-size:1.3rem; font-weight:300; font-style:italic; }
    @media(max-width:640px) { .search-bar{flex-wrap:wrap} .book-grid{grid-template-columns:repeat(2,1fr);gap:1rem} }
</style>

<div class="ocean-page">
    <div class="ocean-bg">
        <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
        <div class="bubble"></div><div class="bubble"></div><div class="bubble"></div>
        <div class="bubble"></div><div class="bubble"></div>
    </div>

    <div class="ocean-content">
        <div class="page-header">
            <p class="eyebrow">OceanLibrary</p>
            <h1>Koleksi <em>Buku</em></h1>
            <p class="subtitle">Temukan lautan pengetahuan yang tak terbatas</p>
        </div>

        <form method="GET" action="/books">
            <div class="search-bar">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis...">
                <select name="kategori">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>{{ $k }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.099zm-5.442 1.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11"/>
                    </svg>
                    Cari
                </button>
            </div>
        </form>

        @if($books->isEmpty())
            <div class="empty-state">
                <div class="icon">🌊</div>
                <p>Tidak ada buku yang ditemukan di kedalaman ini...</p>
            </div>
        @else
        <div class="book-grid">
            @foreach($books as $book)
            <div class="book-card">
                <div class="book-cover">
                    <img src="{{ asset('images/'.$book->gambar) }}" alt="{{ $book->judul }}" loading="lazy">
                    @if($book->kategori)
                        <span class="cover-badge">{{ $book->kategori }}</span>
                    @endif
                </div>
                <div class="book-body">
                    <h3 class="book-title">{{ $book->judul }}</h3>
                    <p class="book-author">{{ $book->penulis }}</p>
                    @if($book->tersedia)
                        <span class="status-badge status-tersedia">Tersedia</span>
                    @else
                        <span class="status-badge status-dipinjam">Dipinjam</span>
                    @endif
                    <a href="/books/{{ $book->id }}" class="btn-detail">Lihat Detail</a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div style="width:100%;overflow:hidden;line-height:0;opacity:.3;margin-top:2rem">
        <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%">
            <path d="M0,30 C360,60 720,0 1080,30 C1260,45 1380,15 1440,30 L1440,60 L0,60 Z" fill="#020d1f"/>
        </svg>
    </div>
</div>

@endsection