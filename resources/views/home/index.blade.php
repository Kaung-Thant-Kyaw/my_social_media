@extends('home.layouts.master')

@section('left-sidebar')
    <div class="sticky top-4 rounded-lg bg-white p-4 shadow">
        {{-- User Profile Link --}}
        <a href="{{ route('user.profile.show', auth()->user()) }}"
            class="flex items-center space-x-3 rounded-lg p-2 hover:bg-gray-100">
            <img src="{{ asset('profile_pictures/' . auth()->user()->avatar) ?? asset('default_user.jpg') }}"
                alt="{{ auth()->user()->name }}" class="h-10 w-10 rounded-full">
            <span class="font-semibold">{{ auth()->user()->name }}</span>
        </a>

        {{-- Navigation Links --}}
        <div class="mt-4 space-y-2">
            <a href="#" class="flex items-center space-x-3 rounded-lg p-2 hover:bg-gray-100">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>Home</span>
            </a>
        </div>

        {{-- Followings List --}}
        <div class="mt-6">
            <h3 class="mb-2 text-sm font-semibold uppercase tracking-wider text-gray-500">
                Following
            </h3>
            <div class="space-y-3">
                @foreach ($followings as $user)
                    <div class="flex items-center justify-between space-x-3">
                        <a href="{{ route('user.profile.show', $user) }}"
                            class="flex items-center justify-between space-x-3">
                            <img src="{{ $user->avatar ? asset('profile_pictures/' . $user->avatar) : asset('default_user.jpg') }}"
                                alt="{{ $user->name }}" class="h-8 w-8 rounded-full">
                            <span class="text-sm">{{ $user->name }}</span>
                        </a>
                        @include('partials.follow', ['user' => $user])
                    </div>
                @endforeach
                @if ($followings->isEmpty())
                    <p class="text-sm text-gray-500">You're not following anyone yet</p>
                @endif
            </div>

        </div>
    </div>
@endsection

@section('content')
    {{-- Search bar Mobile --}}
    <div class="mb-4 md:hidden">
        <div class="relative">
            <input type="text"
                class="w-full rounded-full border border-gray-300 py-2 pl-10 pr-4 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="absolute left-3 top-2.5">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Create Post --}}
    <div class="mb-4 rounded-lg bg-white p-4 shadow">
        <div class="flex space-x-3">
            <a href="{{ route('user.profile.show', auth()->user()) }}">
                <img src="{{ auth()->user()->avatar ? asset('profile_pictures/' . auth()->user()->avatar) : asset('default_user.jpg') }}"
                    alt="{{ auth()->user()->name }}" class="h-10 w-10 rounded-full object-cover">
            </a>

            <div class="flex-1">
                <a href="{{ route('post.create') }}" class="block w-full">
                    <input type="text" placeholder="What's on your mind?"
                        class="w-full cursor-pointer rounded-full bg-gray-100 px-4 py-3 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                        readonly>
                </a>
            </div>
        </div>
    </div>

    {{-- Posts --}}
    @foreach ($posts as $post)
        <div class="mb-4">
            @include('partials.post', ['post' => $post])
        </div>
        {{-- <div class="mb-4 rounded-lg bg-white p-4 shadow-lg">
            <div class="mb-3 flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.profile.show', $post->user) }}" class="shrink-0">
                        <img src="{{ $post->user->avatar ? asset('profile_pictures/' . $post->user->avatar) : asset('default_user.jpg') }}"
                            alt="{{ $post->user->name }}"
                            class="h-10 w-10 rounded-full border-2 border-white object-cover shadow-sm">
                    </a>
                    <div>
                        <a href="{{ route('user.profile.show', $post->user) }}"
                            class="font-semibold transition-colors hover:text-blue-500">
                            {{ $post->user->name }}
                        </a>
                        <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <button class="rounded-full p-1 transition-colors hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                        </path>
                    </svg>
                </button>
            </div>

            @if ($post->content)
                <p class="mb-3 leading-relaxed text-gray-700">{{ $post->content }}</p>
            @endif

            @if ($post->image)
                <img src="{{ asset('posts/' . $post->image) }}" alt="Post image"
                    class="mb-3 w-full rounded-xl border border-gray-100 object-cover">
            @endif

            <div class="flex space-x-3 border-y border-gray-100 py-2 text-gray-500">
                <button
                    class="flex items-center gap-2 rounded-lg px-3 py-2 transition-colors hover:bg-gray-50 hover:text-red-500">
                    <i class="far fa-heart"></i>
                    <span class="text-sm">Like</span>
                </button>
                <button
                    class="flex items-center gap-2 rounded-lg px-3 py-2 transition-colors hover:bg-gray-50 hover:text-blue-500">
                    <i class="far fa-comment"></i>
                    <span class="text-sm">Comment</span>
                </button>
            </div>

            <!-- Comments section -->
        </div> --}}
    @endforeach

    {{ $posts->links() }}
@endsection

@section('right-sidebar')
    <!-- Search Bar (Desktop) -->
    <div class="mb-4 hidden md:block">
        <div class="relative">
            <input type="text" placeholder="Search..."
                class="w-full rounded-full border border-gray-300 py-2 pl-10 pr-4 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="absolute left-3 top-2.5">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Who to Follow -->
    <div class="sticky top-4 rounded-lg bg-white p-4 shadow">
        <h3 class="mb-3 text-lg font-semibold">Who to Follow</h3>
        <div class="space-y-4">
            @foreach ($suggestedUsers as $user)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('user.profile.show', $user) }}">
                            <img src="{{ $user->avatar ? asset('profile_pictures/' . $user->avatar) : asset('default_user.jpg') }}"
                                alt="{{ $user->name }}" class="h-10 w-10 rounded-full">
                        </a>
                        <div>
                            <a href="{{ route('user.profile.show', $user) }}"
                                class="block font-semibold">{{ $user->name }}</a>
                        </div>
                    </div>
                    @include('partials.follow', ['user' => $user])
                </div>
            @endforeach
            @if ($suggestedUsers->isEmpty())
                <p class="text-sm text-gray-500">No suggestions available</p>
            @endif
        </div>
    </div>
@endsection
