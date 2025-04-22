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

        {{-- User Posts --}}
        <div class="mt-4">
            @if (count($user->posts) < 0)
                <p class="text-center text-gray-500">No post yet.</p>
            @else
                <div class="mt-6 space-y-4">
                    @forelse($posts as $post)
                        <div class="rounded-lg bg-white p-4 shadow">
                            {{-- Post Header --}}
                            <div class="mb-3 flex items-center justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $post->user->avatar ? asset('profile_pictures/' . $post->user->avatar) : asset('images/default-avatar.jpg') }}"
                                        class="mr-3 h-10 w-10 rounded-full">
                                    <div>
                                        <p class="font-semibold">{{ $post->user->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $post->created_at->diffForHumans() }}
                                            • {{ ucfirst($post->visibility) }}
                                        </p>
                                    </div>
                                </div>
                                @if (auth()->id() === $post->user_id)
                                    <div class="dropdown">
                                        <button class="text-gray-400 hover:text-gray-600">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        {{-- Dropdown menu for edit/delete --}}
                                        <div class="dropdown-content">
                                            <a href="{{ route('post.edit', $post) }}"
                                                class="text-sm text-blue-500">Edit</a>
                                            <form action="{{ route('post.destroy', $post) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-500">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Post Content --}}
                            @if ($post->content)
                                <p class="mb-4 text-gray-800">{{ $post->content }}</p>
                            @endif

                            {{-- Post Images --}}
                            @if ($post->media->count() > 0)
                                <div class="grid grid-cols-2 gap-2 md:grid-cols-3">
                                    @foreach ($post->media as $media)
                                        <img src="{{ asset('posts/' . $media->file_path) }}" alt="Post image"
                                            class="h-40 w-full rounded object-cover">
                                    @endforeach
                                </div>
                            @endif

                            {{-- Post Actions --}}
                            <div class="mt-4 flex items-center gap-4 border-t pt-3">
                                <button class="flex items-center gap-1 text-gray-500 hover:text-blue-500">
                                    <i class="far fa-heart"></i>
                                    <span>Heart</span>
                                </button>
                                <button class="flex items-center gap-1 text-gray-500 hover:text-green-500">
                                    <i class="far fa-comment"></i>
                                    <span>Comment</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg bg-white p-4 text-center text-gray-500 shadow">
                            No posts found
                        </div>
                    @endforelse
                </div>
        </div>
        @endif
    </div>

@endsection
@push('styles')
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 0.5rem;
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
    </style>
@endpush
