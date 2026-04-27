@extends('layouts.auth')

@section('title', 'Register - Liteverifier')

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md">
    <div class="flex justify-center">
        <a href="{{ url('/') }}" class="text-4xl font-semibold tracking-tight text-blue-800 drop-shadow-sm">
            Lite<span class="text-gray-900">verifier</span>
        </a>
    </div>
    <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
        Create an account
    </h2>
    <p class="mt-2 text-center text-sm text-gray-600">
        Join us today! It takes only a few steps
    </p>
</div>

<div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
    <div class="bg-white py-8 px-4 shadow sm:rounded-xl sm:px-10 border border-gray-100">
        @include('common.message')

        <form class="space-y-6" method="POST" action="{{ route('auth.register') }}" novalidate>
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Full Name</label>
                    <div class="mt-2">
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('name') ring-red-300 focus:ring-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium leading-6 text-gray-900">Phone Number</label>
                    <div class="mt-2">
                        <input id="phone_number" name="phone_number" type="tel" maxlength="11" value="{{ old('phone_number') }}" required
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('phone_number') ring-red-300 focus:ring-red-500 @enderror">
                    </div>
                    @error('phone_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required
                        class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('email') ring-red-300 focus:ring-red-500 @enderror">
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('password') ring-red-300 focus:ring-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Confirm Password</label>
                    <div class="mt-2">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>

            <div>
                <label for="referral_code" class="block text-sm font-medium leading-6 text-gray-900">Referral Code (Optional)</label>
                <div class="mt-2">
                    <input id="referral_code" name="referral_code" type="text" maxlength="6" value="{{ old('referral_code') }}"
                        class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-800 sm:text-sm sm:leading-6 @error('referral_code') ring-red-300 focus:ring-red-500 @enderror">
                </div>
                @error('referral_code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-start">
                <div class="flex h-6 items-center">
                    <input id="terms" name="terms" type="checkbox" value="1" {{ old('terms') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-blue-800 focus:ring-blue-800 @error('terms') border-red-300 @enderror">
                </div>
                <div class="ml-3 text-sm leading-6">
                    <label for="terms" class="text-gray-900">I agree to all <a href="#" class="font-semibold text-blue-800 hover:text-blue-700">Terms & Conditions</a></label>
                    @error('terms')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="flex w-full justify-center rounded-md bg-blue-800 py-2.5 px-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-800 transition-colors">
                    Create Account
                </button>
            </div>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            Already have an account? 
            <a href="{{ route('auth.login') }}" class="font-semibold text-blue-800 hover:text-blue-700">Sign in</a>
        </div>
    </div>
</div>
@endsection
