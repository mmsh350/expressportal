@extends('layouts.auth')
@section('title', 'Forget Password - Expressportal')
@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md">
    <div class="flex justify-center">
        <a href="{{ url('/') }}" class="text-4xl font-semibold tracking-tight text-blue-800 drop-shadow-sm">
            Express<span class="text-gray-900">portal</span>
        </a>
    </div>
    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
        Forgot your password?
    </h2>
    <p class="mt-2 text-center text-sm text-gray-600">
        Enter your email to receive a password reset link
    </p>
</div>

<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
    <div class="bg-white py-8 px-4 shadow sm:rounded-xl sm:px-10 border border-gray-100">
        @include('common.message')

        <form class="space-y-6" method="POST" action="{{ route('auth.password.email') }}">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required autofocus
                        class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('email') ring-red-300 focus:ring-red-500 @enderror">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 justify-center rounded-md bg-blue-800 py-2.5 px-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-800 transition-colors">
                    Send Link
                </button>
                <a href="{{ route('auth.login') }}" class="flex-1 flex justify-center items-center rounded-md bg-white py-2.5 px-3 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
