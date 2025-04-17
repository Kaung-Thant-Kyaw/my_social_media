@extends('authentication.layouts.master')

@section('content')
    <div class="sm:mx-auto sm:w-full sm:max-w-md">

        <div class="mt-8 overflow-hidden rounded-lg bg-white p-5 shadow">
            <div class="mx-auto mb-3 text-center">
                <h2 class="text-3xl font-bold text-gray-900">
                    Create account
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}"
                        class="font-medium text-indigo-600 transition-colors hover:text-indigo-500">
                        Sign in
                    </a>
                </p>
            </div>
            <div class="px-6 py-2 sm:p-10">
                <form class="space-y-6" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">
                            Name
                        </label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" autocomplete="name"
                                class="block w-full rounded-md border border-gray-300 px-4 py-2 shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ old('name') }}" placeholder="Enter your name">
                            @error('name')
                                <small class="mt-1 text-red-600">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email"
                                class="block w-full rounded-md border border-gray-300 px-4 py-2 shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                value="{{ old('email') }}" placeholder="you@example.com">
                            @error('email')
                                <small class="mt-1 text-red-600">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password
                            </label>
                        </div>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="new-password"
                                class="block w-full rounded-md border border-gray-300 px-4 py-2 shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="••••••••">
                            @error('password')
                                <small class="mt-1 text-red-600">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Confirm Password
                            </label>
                        </div>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                autocomplete="new-password"
                                class="block w-full rounded-md border border-gray-300 px-4 py-2 shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="••••••••">
                            @error('password_confirmation')
                                <small class="mt-1 text-red-600">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Sign up
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-white px-2 text-gray-500">
                                Or sign in with
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div>
                            <a href="#"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <i class="fa-brands fa-google h-5 w-5 text-red-500"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <i class="fa-brands fa-github h-5 w-5 text-gray-800"></i>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
