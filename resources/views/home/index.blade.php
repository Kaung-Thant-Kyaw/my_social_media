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
        <div class="mb-4 rounded-lg bg-white p-4 shadow">
            <div class="mb-3 flex items-start justify-between">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('user.profile.show', $post->user) }}">
                        <img src="{{ $post->user->avatar ? asset('profile_pictures/' . $post->user->avatar) : asset('default_user.jpg') }}"
                            alt="{{ $post->user->name }}" class="h-10 w-10 rounded-full">
                    </a>
                    <div>
                        <a href="{{ route('user.profile.show', $post->user) }}"
                            class="font-semibold">{{ $post->user->name }}</a>
                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <button class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                        </path>
                    </svg>
                </button>
            </div>

            @if ($post->content)
                <p class="mb-3">{{ $post->content }}</p>
            @endif

            @if ($post->image)
                <img src="{{ asset('posts/' . $post->image) }}" alt="Post image" class="mb-3 w-full rounded-lg">
            @endif

            <div class="mb-3 flex justify-between border-b border-t border-gray-100 py-2 text-gray-500">
                <button class="flex items-center space-x-1 hover:text-blue-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                        </path>
                    </svg>
                    <span>Like</span>
                </button>
                <button class="flex items-center space-x-1 hover:text-blue-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <span>Comment</span>
                </button>
                <button class="flex items-center space-x-1 hover:text-blue-500">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z">
                        </path>
                    </svg>
                    <span>Share</span>
                </button>
            </div>

            <!-- Comments section would go here -->
        </div>
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
