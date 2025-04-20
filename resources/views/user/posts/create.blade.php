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
                            <option value="public">🌍 Public</option>
                            <option value="friend">👥 Friends</option>
                            <option value="private">🔒 Private</option>
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
            let uploadedFiles = []; // ရွေးထားတဲ့ဖိုင်တွေကို စီမံဖို့ array

            // 1. ဖိုင်ရွေးလိုက်တိုင်း Preview ပြခြင်း
            input.addEventListener('change', function() {
                uploadedFiles = Array.from(this.files); // ဖိုင်တွေကို array အဖြစ်သိမ်း
                preview.innerHTML = '';

                uploadedFiles.forEach((file, index) => {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className =
                            'size-40 rounded object-cover border cursor-move draggable';
                        img.draggable = true; // Drag လုပ်လို့ရအောင်
                        img.dataset.index = index; // မူလအစီအစဥ်ကို သိမ်းထား

                        // Drag & Drop Event Listeners
                        img.addEventListener('dragstart', handleDragStart);
                        img.addEventListener('dragover', handleDragOver);
                        img.addEventListener('drop', handleDrop);

                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            });

            // 2. Drag & Drop Logic
            let draggedIndex = null;

            function handleDragStart(e) {
                draggedIndex = parseInt(e.target.dataset.index);
                e.dataTransfer.effectAllowed = 'move';
            }

            function handleDragOver(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            }

            function handleDrop(e) {
                e.preventDefault();
                const targetIndex = parseInt(e.target.dataset.index);
                swapImages(draggedIndex, targetIndex); // ပုံတွေကို နေရာပြောင်း
                updateFileInputOrder(); // File Input ကို update လုပ်
            }

            // 3. ပုံတွေရဲ့ နေရာပြောင်းလဲမှု
            function swapImages(oldIndex, newIndex) {
                // ပုံတွေကို array ထဲမှာ ပြန်စီ
                [uploadedFiles[oldIndex], uploadedFiles[newIndex]] = [uploadedFiles[newIndex], uploadedFiles[
                    oldIndex]];

                // Preview ကို update လုပ်
                preview.innerHTML = '';
                uploadedFiles.forEach((file, index) => {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.className = 'size-40 rounded object-cover border cursor-move';
                    img.draggable = true;
                    img.dataset.index = index;
                    img.addEventListener('dragstart', handleDragStart);
                    img.addEventListener('dragover', handleDragOver);
                    img.addEventListener('drop', handleDrop);
                    preview.appendChild(img);
                });
            }

            // 4. Form Submit မှာ File Order ကို update လုပ်
            document.querySelector('form').addEventListener('submit', function(e) {
                const dataTransfer = new DataTransfer();
                uploadedFiles.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files; // အသစ်စီထားတဲ့ဖိုင်တွေကို ထည့်
            });
        });
    </script>
@endpush
