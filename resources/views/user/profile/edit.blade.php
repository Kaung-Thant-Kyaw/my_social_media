@extends('user.layouts.master')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-lg bg-white shadow">
            <!-- Header -->
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900">Edit Profile</h2>
            </div>

            <!-- Form -->
            <form action="{{ route('user.profile.update', auth()->id()) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6 px-6 py-4">
                    <!-- Avatar Upload -->
                    <div class="flex items-center">
                        <div class="mr-4">
                            <img id="avatar-preview" class="h-20 w-20 rounded-full object-cover"
                                src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.jpg') }}"
                                alt="Current avatar">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*">
                                <button type="button" onclick="document.getElementById('avatar').click()"
                                    class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Change
                                </button>
                            </div>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                        <textarea id="bio" name="bio" rows="3"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Update -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Change Password</h3>

                        @if ($user->password)
                            <div class="mb-4">
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Current
                                    Password</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <p class="mb-4 text-sm text-gray-500">You registered via social account. Set a password below.
                            </p>
                        @endif

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <input type="password" name="password" id="password"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="flex justify-end border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <button type="button" onclick="window.location='{{ route('user.profile.show', $user) }}'"
                        class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                        class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Preview avatar before upload
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('avatar-preview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
