<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Log in</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="bg-[#0f172a] min-h-screen flex items-center justify-center px-4 relative overflow-hidden">
    <!-- Sophisticated Background Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px]"></div>

    <div
        class="w-full max-w-md bg-white/80 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl p-8 relative z-10 animate-scale-in">

        <div class="text-center mb-8">
            <div class="flex justify-center mb-6 animate-fade-in">
                <img src="{{ asset('gfivetech.jpeg') }}" alt="GfiveTech Logo" class="h-16 w-auto rounded-xl shadow-md">
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight animate-slide-up animate-stagger-1">
                Welcome Back
            </h1>
            <p class="mt-2 text-slate-500 font-medium animate-slide-up animate-stagger-2">
                Sign in to manage your account
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div class="animate-slide-up animate-stagger-3">
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                    Email address
                </label>

                <div class="relative group">
                    <input id="email"
                        class="block w-full rounded-xl border border-slate-200 bg-white/50 px-4 py-3 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 focus:outline-none sm:text-sm @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                        type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username" placeholder="name@company.com" />
                </div>

                @error('email')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="animate-slide-up animate-stagger-3">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                    Password
                </label>

                <div class="relative group">
                    <input id="password"
                        class="block w-full rounded-xl border border-slate-200 bg-white/50 px-4 py-3 text-slate-900 placeholder-slate-400 transition-all duration-200 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 focus:outline-none sm:text-sm @error('password') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••" />
                </div>

                @error('password')
                    <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="animate-slide-up animate-stagger-4 pt-2">
                <button type="submit"
                    class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-indigo-600/20 active:scale-[0.98] transition-all duration-200">
                    Sign in
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <p class="mt-8 text-center text-sm font-medium text-slate-500 animate-fade-in animate-stagger-4">
                Don't have an account?
                <a href="{{ route('register') }}"
                    class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                    Create account
                </a>
            </p>
        @endif

    </div>

</body>

</html>
