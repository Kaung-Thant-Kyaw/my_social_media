@extends('user.layouts.master')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-4">
            <a href="{{ route('home') }}" class="text-blue-500 hover:underline">← Home</a>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow">
            {{-- COVER PHOTO --}}
            <form id="cover-form" action="{{ route('user.profile.change-cover-picture') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <label for="cover-input">
                    <div class="relative h-48 cursor-pointer bg-gradient-to-r from-blue-500 to-purple-600">
                        @if ($user->cover)
                            <img src="{{ asset('cover_pictures/' . $user->cover) }}" alt=""
                                class="absolute inset-0 h-full w-full object-cover">
                        @endif
                        @if (auth()->id() === $user->id)
                            <div class="absolute right-2 top-2 rounded bg-white/70 px-3 py-1 text-sm shadow">
                                {{ $user->cover ? 'Change' : 'Upload' }} Cover
                            </div>
                        @endif
                    </div>
                </label>
                <input id="cover-input" name="cover" type="file" class="hidden"
                    onchange="document.getElementById('cover-form').submit()">
            </form>

            {{-- PROFILE + INFO --}}
            <div class="relative -mt-16 px-6 py-4">
                <div class="flex items-center">
                    {{-- AVATAR --}}
                    <form id="avatar-form" action="{{ route('user.profile.change-profile-picture') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <label for="avatar-input" class="group relative inline-block">
                            <img class="h-32 w-32 cursor-pointer rounded-full border-4 border-white object-cover shadow-lg"
                                src="{{ $user->avatar ? asset('profile_pictures/' . $user->avatar) : asset('images/default-avatar.jpg') }}"
                                alt="{{ $user->name }}">

                            {{-- Hover overlay --}}
                            <div
                                class="absolute inset-0 flex items-center justify-center rounded-full bg-gray-500 bg-opacity-50 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <span class="text-sm font-semibold text-white">{{ $user->avatar ? 'Change' : 'Upload' }}
                                    Profile</span>
                            </div>
                        </label>

                        <input id="avatar-input" name="avatar" type="file" class="hidden"
                            onchange="document.getElementById('avatar-form').submit()">
                    </form>

                    {{-- NAME + USERNAME + BUTTON --}}
                    <div class="ml-6 flex-1">
                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            </div>
                            @if (auth()->id() === $user->id)
                                <div class="flex gap-2">
                                    <a href="{{ route('user.profile.edit', auth()->id()) }}"
                                        class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                        <i class="fa-solid fa-user"></i> Edit Profile
                                    </a>
                                    <a href="{{ route('user.profile.change-password-page', auth()->user()) }}"
                                        class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                        <i class="fa-solid fa-lock"></i> Change Password
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- BIO --}}
                        <p class="mt-2 text-gray-600">{{ $user->bio ?? 'No bio yet.' }}</p>
                    </div>
                </div>
            </div>

            {{-- STATUS MESSAGE --}}
            @if (session('status'))
                <div class="px-6 pb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            {{-- SOCIAL ACCOUNTS --}}
            @if (auth()->id() === $user->id)
                <div class="border-t border-gray-200 px-6 py-4">
                    <h3 class="mb-2 text-lg font-medium text-gray-900">Connected Accounts</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($user->socialAccounts as $account)
                            <div class="flex items-center rounded-full bg-gray-100 px-4 py-2">
                                <span class="text-gray-700">{{ ucfirst($account->provider) }}</span>
                                @if ($account->is_primary)
                                    <span
                                        class="ml-2 rounded-full bg-blue-100 px-2 py-1 text-xs text-blue-800">Primary</span>
                                @else
                                    <form class="ml-2" action="{{ route('social.accounts.destroy', $account) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-1 text-red-500 hover:text-red-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach

                        @foreach (['google', 'github'] as $provider)
                            @if (!$user->socialAccounts->contains('provider', $provider))
                                <a href="{{ route('social.login.redirect', $provider) }}"
                                    class="flex items-center rounded-full bg-gray-100 px-4 py-2 hover:bg-gray-200">
                                    <span class="text-gray-700">Connect {{ ucfirst($provider) }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        {{-- POST CREATION FORM --}}
        @if (auth()->id() === $user->id)
            <div class="mt-4">
                <a href="{{ route('post.create') }}">
                    <textarea readonly class="w-full cursor-pointer rounded-md border p-3 text-sm text-gray-500" rows="1"
                        placeholder="What's on your mind?"></textarea>
                </a>
            </div>
        @endif

    @endsection
