@auth
    @if (auth()->id() !== $user->id)
        <form action="{{ auth()->user()->isFollowing($user) ? route('user.unfollow', $user) : route('user.follow', $user) }}"
            method="POST">
            @csrf
            <button type="submit"
                class="{{ auth()->user()->isFollowing($user)
                    ? 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                    : 'bg-blue-600 text-white hover:bg-blue-700' }} rounded px-4 py-2 text-sm font-medium transition-colors">
                {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
            </button>
        </form>
    @endif
@endauth
