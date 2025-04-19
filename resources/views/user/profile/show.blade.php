@extends('user.layouts.master')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="mb-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-blue-500 hover:underline">Home</a>
        </div>
        {{-- Profile Header  --}}
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <!-- Cover Photo -->
            <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600"></div>

            <!-- Profile Info -->
            <div class="relative -mt-16 px-6 py-4">
                <!-- Avatar -->
                <div class="flex items-center">
                    <img class="h-32 w-32 rounded-full border-4 border-white shadow-lg"
                        src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.jpg') }}"
                        alt="{{ $user->name }}">

                    <!-- User Actions -->
                    <div class="ml-6 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                                <p class="text-gray-600">@<span>{{ $user->username }}</span></p>
                            </div>

                            @if (auth()->id() === $user->id)
                                <a href="{{ route('user.profile.edit', auth()->id()) }}"
                                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Edit Profile
                                </a>
                            @endif
                        </div>

                        <!-- Bio -->
                        <p class="mt-2 text-gray-600">{{ $user->bio ?? 'No bio yet' }}</p>

                        <!-- Stats -->
                        {{-- <div class="mt-4 flex space-x-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-2 text-gray-600">{{ $user->posts_count }} Posts</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z">
                                    </path>
                                </svg>
                                <span class="ml-2 text-gray-600">{{ $user->followers_count }} Followers</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                    </path>
                                </svg>
                                <span class="ml-2 text-gray-600">{{ $user->following_count }} Following</span>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <!-- Social Connections -->
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
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
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
                            @if (session('status'))
                                <div class="mt-2 text-sm text-green-600">
                                    {{ session('status') }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- User Posts -->
        {{-- <div class="mt-8">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Posts</h2>
            @if ($posts->count())
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($posts as $post)
                        @include('posts.partials.card', ['post' => $post])
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @else
                <p class="text-gray-500">No posts yet.</p>
            @endif
        </div> --}}
    </div>

@endsection
