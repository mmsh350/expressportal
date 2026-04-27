@extends('layouts.auth')

@section('title', 'Login - Liteverifier')

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md">
    <div class="flex justify-center">
        <a href="{{ url('/') }}" class="text-4xl font-semibold tracking-tight text-blue-800 drop-shadow-sm">
            Lite<span class="text-gray-900">verifier</span>
        </a>
    </div>
    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
        Welcome back
    </h2>
    <p class="mt-2 text-center text-sm text-gray-600">
        Sign in to your account to continue
    </p>
</div>

<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-xl sm:px-10 border border-gray-100">
        @include('common.message')

        <form class="space-y-6" method="POST" action="{{ route('auth.login') }}" novalidate>
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address or Username</label>
                <div class="mt-2">
                    <input id="email" name="email" type="text" autocomplete="email" value="{{ old('email') }}" required autofocus
                        class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('email') ring-red-300 focus:ring-red-500 @enderror">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <div class="mt-2">
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('password') ring-red-300 focus:ring-red-500 @enderror">
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-800 focus:ring-blue-800">
                    <label for="remember" class="ml-3 block text-sm leading-6 text-gray-900">Remember me</label>
                </div>

                <div class="text-sm leading-6">
                    <a href="{{ route('auth.password.request') }}" class="font-semibold text-blue-800 hover:text-blue-700">Forgot password?</a>
                </div>
            </div>

            <div>
                <button type="submit" class="flex w-full justify-center rounded-md bg-blue-800 py-2.5 px-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-800 transition-colors">
                    Sign in
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Don't have an account? 
            <a href="{{ route('auth.register') }}" class="font-semibold text-blue-800 hover:text-blue-700">Create one</a>
        </div>
    </div>
</div>
@endsection
