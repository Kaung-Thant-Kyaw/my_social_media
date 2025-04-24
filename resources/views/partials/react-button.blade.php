<div class="like-container" data-post-id="{{ $post->id }}">
    <button type="button" class="like-button flex items-center gap-2 rounded-lg px-3 py-2 transition-colors hover:bg-gray-50 hover:text-red-500"
            data-liked="{{ $post->isLikedByAuthUser() ? 'true' : 'false' }}"
            data-count="{{ $post->reactions_count }}">
        <i class="{{ $post->isLikedByAuthUser() ? 'fas text-red-500' : 'far' }} fa-heart"></i>
        <span class="text-sm like-count">
            {{ $post->reactions_count }} {{ Str::plural('Like', $post->reactions_count) }}
        </span>
    </button>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Use event delegation for dynamic content
        $(document).on('click', '.like-button', function() {
            const button = $(this);
            const container = button.closest('.like-container');
            const postId = container.data('post-id');
            const icon = button.find('i');
            const countElement = button.find('.like-count');
            
            // Get current state
            const isLiked = button.data('liked') === 'true';
            let currentCount = parseInt(button.data('count'));
            
            // Prevent multiple clicks
            if (button.hasClass('processing')) return;
            button.addClass('processing');
            
            // Optimistic UI update
            const newLikeState = !isLiked;
            const newCount = newLikeState ? currentCount + 1 : currentCount - 1;
            
            button.data('liked', newLikeState ? 'true' : 'false');
            button.data('count', newCount);
            
            icon.removeClass(isLiked ? 'fas text-red-500' : 'far')
                .addClass(newLikeState ? 'fas text-red-500' : 'far');
            
            countElement.text(newCount + ' ' + (newCount === 1 ? 'Like' : 'Likes'));
            
            // AJAX request
            $.ajax({
                url: '/reactions/toggle',
                method: 'POST',
                data: {
                    reactable_id: postId,
                    reactable_type: 'App\\Models\\Post',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Verify server response matches our expectation
                    if ((response.status === 'liked') !== newLikeState) {
                        // Revert if mismatch
                        button.data('liked', isLiked ? 'true' : 'false');
                        button.data('count', currentCount);
                        icon.removeClass(newLikeState ? 'fas text-red-500' : 'far')
                            .addClass(isLiked ? 'fas text-red-500' : 'far');
                        countElement.text(currentCount + ' ' + (currentCount === 1 ? 'Like' : 'Likes'));
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    // Revert on error
                    button.data('liked', isLiked ? 'true' : 'false');
                    button.data('count', currentCount);
                    icon.removeClass(newLikeState ? 'fas text-red-500' : 'far')
                        .addClass(isLiked ? 'fas text-red-500' : 'far');
                    countElement.text(currentCount + ' ' + (currentCount === 1 ? 'Like' : 'Likes'));
                },
                complete: function() {
                    button.removeClass('processing');
                }
            });
        });
    });
</script>
@endpush
