@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <h1 class="text-2xl font-semibold">
        Dashboard
    </h1>

    <div class="bg-white p-6 rounded-xl shadow-sm">

        <h2 class="text-lg font-semibold mb-4">Profil Saya</h2>

        <p><b>Nama:</b> {{ auth()->user()?->name }}</p>
        <p><b>Kelas:</b> {{ auth()->user()?->kelas }}</p>
        <p><b>NIS:</b> {{ auth()->user()?->nis }}</p>

        

     
    </div>

</div>

@endsection