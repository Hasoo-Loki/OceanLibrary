<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - OceanLibrary</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-blue-700 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full">
        <!-- Logo/Title -->
        <div class="text-center mb-8">
            <div class="inline-block bg-[#000099] text-white p-3 rounded-full mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#000099]">Admin Login</h1>
            <p class="text-sm text-gray-600 mt-2">OceanLibrary Administration</p>
        </div>

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login.admin') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Email Admin
                </label>
                <input type="email" name="email" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#000099] focus:border-transparent"
                       placeholder="akun admin">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Password
                </label>
                <input type="password" name="password" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#000099] focus:border-transparent"
                       placeholder="••••••••">
            </div>

            <button type="submit" 
                    class="w-full bg-[#000099] text-white py-3 rounded-lg hover:bg-[#000077] transition font-semibold shadow-lg hover:shadow-xl">
                Login ke Dashboard
            </button>
        </form>

        <!-- Back to User Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-[#000099] transition inline-flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Login User
            </a>
        </div>
    </div>

</body>
</html>