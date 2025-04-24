@extends('user.layouts.master')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            {{-- Header --}}
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-center text-xl font-semibold text-gray-900">Create New Post</h2>
            </div>

            {{-- Form --}}
            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6 px-6 py-4">
                    {{-- Content --}}
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">What's on your mind?</label>
                        <textarea id="content" name="content" rows="3"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                            placeholder="Share your thoughts...">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Images --}}
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700">Upload Images</label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Preview --}}
                        <div id="image-preview"
                            class="mt-4 flex flex-wrap gap-3 rounded border border-gray-200 bg-gray-50 p-3"></div>
                    </div>

                    {{-- Visibility --}}
                    <div>
                        <label for="visibility" class="block text-sm font-medium text-gray-700">Post Visibility</label>
                        <select name="visibility" id="visibility"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="public" @selected(old('visibility') == 'public')>🌍 Public</option>
                            <option value="private" @selected(old('visibility') == 'private')>🔒 Private</option>
                        </select>
                        @error('visibility')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Footer --}}
                <div class="flex justify-end border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <button type="button" onclick="window.history.back()"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Post
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('images');
            const preview = document.getElementById('image-preview');
            let uploadedFiles = [];

            // File input change handler
            input.addEventListener('change', function() {
                uploadedFiles = Array.from(this.files);
                updatePreview();
            });

            // Preview update function
            function updatePreview() {
                preview.innerHTML = ''; // Clear existing previews

                uploadedFiles.forEach((file, index) => {
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'size-40 object-cover rounded border border-gray-300';
                        img.draggable = true;
                        img.dataset.index = index;

                        // Drag & Drop handlers
                        img.addEventListener('dragstart', handleDragStart);
                        img.addEventListener('dragover', handleDragOver);
                        img.addEventListener('drop', handleDrop);
                        img.addEventListener('dragend', handleDragEnd);

                        preview.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                });
            }

            // Drag & Drop functions
            let draggedIndex = null;

            function handleDragStart(e) {
                draggedIndex = parseInt(e.target.dataset.index);
                e.dataTransfer.effectAllowed = 'move';
            }

            function handleDragOver(e) {
                e.preventDefault(); // Required for drop to work
                e.dataTransfer.dropEffect = 'move';
            }

            function handleDrop(e) {
                e.preventDefault();
                const targetIndex = parseInt(e.target.dataset.index);
                swapImages(draggedIndex, targetIndex);
                updatePreview();
                updateFileInput();
            }

            function handleDragEnd() {
                draggedIndex = null;
            }

            // Swap images in array
            function swapImages(oldIndex, newIndex) {
                [uploadedFiles[oldIndex], uploadedFiles[newIndex]] = [uploadedFiles[newIndex], uploadedFiles[
                    oldIndex]];
            }

            // Update actual file input
            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;
                console.log('Updated Files', input.files.length);
            }

            // Ensure file input updates before form submit
            document.querySelector('form').addEventListener('submit', function(e) {
                console.log('Final files:', document.getElementById('images').files);
                updateFileInput();
            });
        });
    </script>
@endpush
