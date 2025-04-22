@extends('user.layouts.master')

@section('content')
    <div class="mx-auto max-w-4xl p-6">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <div class="border-b bg-gray-50 px-6 py-4">
                <h1 class="text-xl font-semibold">Edit Post</h1>
            </div>

            <form action="{{ route('post.update', $post) }}" method="POST" enctype="multipart/form-data" id="post-form">
                @csrf
                @method('PUT')

                <div class="space-y-6 p-6">
                    <!-- Content Field -->
                    <div>
                        <label class="mb-2 block text-sm font-medium">Post Content</label>
                        <textarea name="content" rows="4" class="w-full rounded-md border px-3 py-2" placeholder="Write your thoughts...">{{ old('content', $post->content) }}</textarea>
                    </div>

                    <!-- Existing Images Section -->
                    <div class="image-container mb-4 flex flex-wrap gap-3">
                        @foreach ($post->media->sortBy('order') as $media)
                            <div class="draggable-item group relative" data-id="{{ $media->id }}"
                                data-index="{{ $loop->index }}">
                                <img src="{{ asset('posts/' . $media->file_path) }}"
                                    class="h-32 w-32 cursor-move rounded border object-cover">
                                <button type="button"
                                    class="delete-btn absolute right-1 top-1 h-6 w-6 rounded-full bg-red-500 text-white opacity-0 transition group-hover:opacity-100"
                                    data-id="{{ $media->id }}">
                                    ×
                                </button>
                                <input type="hidden" name="media_order[]" value="{{ $media->id }}">
                            </div>
                        @endforeach
                    </div>

                    <!-- New Images Upload -->
                    <div class="mb-4">
                        <input type="file" name="images[]" id="new-images" multiple class="hidden" accept="image/*">
                        <button type="button" onclick="document.getElementById('new-images').click()"
                            class="rounded bg-blue-500 px-4 py-2 text-white">
                            Add New Images
                        </button>
                        <div id="new-images-preview" class="mt-2 flex flex-wrap gap-3"></div>
                    </div>

                    <!-- Deleted Images Storage -->
                    <input type="hidden" name="deleted_images" id="deleted-images" value="">

                    <!-- Visibility -->
                    <div>
                        <label class="mb-2 block text-sm font-medium">Visibility</label>
                        <select name="visibility" class="w-full rounded-md border px-3 py-2">
                            <option value="public" @selected(old('visibility', $post->visibility) == 'public')>Public</option>
                            <option value="friend" @selected(old('visibility', $post->visibility) == 'friend')>Friends</option>
                            <option value="private" @selected(old('visibility', $post->visibility) == 'private')>Private</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t bg-gray-50 px-6 py-4">
                    <button type="button" onclick="history.back()"
                        class="rounded-md border bg-white px-4 py-2 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let draggedItem = null;
            const container = document.querySelector('.image-container');
            const deletedImages = document.getElementById('deleted-images');

            // Existing Images Drag & Drop
            container.querySelectorAll('.draggable-item').forEach(item => {
                item.draggable = true;

                item.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    setTimeout(() => this.classList.add('opacity-50'), 0);
                });

                item.addEventListener('dragend', function() {
                    this.classList.remove('opacity-50');
                });

                item.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    const afterElement = getDragAfterElement(container, e.clientY);
                    const currentItem = draggedItem;
                    if (afterElement == null) {
                        container.appendChild(draggedItem);
                    } else {
                        container.insertBefore(draggedItem, afterElement);
                    }
                    updateMediaOrder();
                });
            });

            // Delete Existing Image
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const currentValue = deletedImages.value;
                    deletedImages.value = currentValue ? `${currentValue},${id}` : id;
                    this.parentElement.remove();
                    updateMediaOrder();
                });
            });

            // Handle New Images
            document.getElementById('new-images').addEventListener('change', function(e) {
                const preview = document.getElementById('new-images-preview');
                preview.innerHTML = '';

                Array.from(this.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const div = document.createElement('div');
                        div.className = 'relative draggable-item';
                        div.innerHTML = `
    <img src="${e.target.result}" class="h-32 w-32 cursor-move rounded border object-cover">
    <input type="hidden" name="media_order[]" value="new_${index}">
    `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // Helper Functions
            function getDragAfterElement(container, y) {
                const draggableElements = [...container.querySelectorAll('.draggable-item:not(.dragging)')];

                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = y - box.top - box.height / 2;
                    if (offset < 0 && offset > closest.offset) {
                        return {
                            offset: offset,
                            element: child
                        };
                    } else {
                        return closest;
                    }
                }, {
                    offset: Number.NEGATIVE_INFINITY
                }).element;
            }

            function updateMediaOrder() {
                const orderInputs = container.querySelectorAll('input[name="media_order[]"]');
                orderInputs.forEach((input, index) => {
                    const mediaId = input.closest('.draggable-item').dataset.id;
                    if (mediaId) input.value = mediaId;
                });
            }
        });
    </script>
@endpush
