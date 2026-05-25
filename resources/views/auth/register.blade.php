{{-- resources/views/auth/register.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-950 px-4 py-10">

    <div class="w-full max-w-md bg-white/5 backdrop-blur-lg border border-white/10 rounded-3xl p-8 shadow-2xl">

        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white">Create Account</h1>
            <p class="text-gray-400 mt-2">Join the FIFA Prediction Platform</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500 text-red-400 rounded-xl p-4">
                <ul class="space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block text-sm text-gray-300 mb-2">
                    Full Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter your name">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm text-gray-300 mb-2">
                    Email Address
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter your email">
            </div>

            {{-- Country --}}
            <div>
                <label class="block text-sm text-gray-300 mb-2">
                    Country
                </label>

                <input type="text"
                       name="country"
                       value="{{ old('country') }}"
                       class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter your country">
            </div>

            {{-- Avatar --}}
            <div>
                <label class="block text-sm text-gray-300 mb-2">
                    Profile Avatar
                </label>

                <input type="file"
                       name="avatar"
                       accept="image/*"
                       class="w-full rounded-xl bg-white/10 border border-white/10 text-gray-300 px-4 py-3 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white hover:file:bg-blue-700">
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm text-gray-300 mb-2">
                    Password
                </label>

                <input type="password"
                       name="password"
                       required
                       class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter password">
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm text-gray-300 mb-2">
                    Confirm Password
                </label>

                <input type="password"
                       name="password_confirmation"
                       required
                       class="w-full rounded-xl bg-white/10 border border-white/10 text-white px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Confirm password">
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition rounded-xl py-3 text-white font-semibold">
                Register
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">
                Login
            </a>
        </div>
    </div>
</div>
@endsection