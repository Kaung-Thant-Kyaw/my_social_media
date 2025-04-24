<div class="mb-4 rounded-lg bg-white p-4 shadow-lg">
    {{-- Post Header --}}
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
                <p class="text-xs text-gray-400">
                    {{ $post->created_at->diffForHumans() }}
                    {{ $post->visibility }}
                </p>
            </div>
        </div>

        {{-- Dropdown --}}
        @if (auth()->id() === $post->user_id)
            <div class="relative">
                <button
                    class="dropdown-toggle rounded-full p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z">
                        </path>
                    </svg>
                </button>
                <div class="dropdown-menu absolute right-0 z-50 mt-2 hidden w-32 rounded-md bg-white py-1 shadow-lg">
                    <a href="{{ route('post.edit', $post) }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Edit
                    </a>
                    <form action="{{ route('post.destroy', $post) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 text-left text-sm text-red-500 hover:bg-gray-100">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    {{-- Post Content --}}
    @if ($post->content)
        <p class="mb-3 leading-relaxed text-gray-700">{{ $post->content }}</p>
    @endif

    {{-- Media Handling --}}
    @if ($post->image)
        <img src="{{ asset('posts/' . $post->image) }}" alt="Post image"
            class="mb-3 w-full rounded-xl border border-gray-100 object-cover">
    @elseif($post->media->count() > 0)
        <div class="grid-cols-{{ $fullView ? '1' : '2' }} md:grid-cols-{{ $fullView ? '1' : '3' }} grid gap-2">
            @foreach ($post->media as $media)
                <img src="{{ asset('posts/' . $media->file_path) }}"
                    class="{{ $fullView ? 'h-[500px] w-full' : 'h-40 w-full' }} rounded object-cover">
            @endforeach
        </div>
    @endif

    {{-- Post Actions --}}
    <div class="flex space-x-3 border-y border-gray-100 py-2 text-gray-500">
        {{-- Like Button --}}
        @include('partials.react-button', ['post' => $post])

        {{-- Comment Button --}}
        <button
            class="flex items-center gap-2 rounded-lg px-3 py-2 transition-colors hover:bg-gray-50 hover:text-blue-500">
            <i class="far fa-comment"></i>
            <span class="text-sm"> Comments</span>
        </button>
    </div>

    {{-- Comments Section --}}

    @if (isset($showComments))
        <div class="mt-4 space-y-4">
            @foreach ($post->comments as $comment)
                @include('partials.comment', ['comment' => $comment])
            @endforeach
        </div>
    @endif

</div>

@once
    @push('scripts')
        <script>
            $(document).ready(function() {
                // Dropdown Logic
                $('.dropdown-toggle').click(function(e) {
                    e.stopPropagation();
                    $(this).siblings('.dropdown-menu').toggleClass('hidden');
                    $('.dropdown-menu').not($(this).siblings('.dropdown-menu')).addClass('hidden');
                });

                $(document).click(function() {
                    $('.dropdown-menu').addClass('hidden');
                });
            });
        </script>
    @endpush
@endonce
