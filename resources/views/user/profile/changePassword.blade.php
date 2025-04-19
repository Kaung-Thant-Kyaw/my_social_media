@extends('user.layouts.master')

@section('content')
    <div class="mx-auto max-w-xl py-10">
        <h2 class="mb-4 text-xl font-semibold">Change Password</h2>
        <form method="POST" action="{{ route('user.profile.change-password') }}">
            @csrf

            <div class="mb-4">
                <label for="current_password" class="mb-1 block text-sm text-gray-700">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="w-full rounded border px-3 py-2"
                    required>
                @error('current_password')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="new_password" class="mb-1 block text-sm text-gray-700">New Password</label>
                <input type="password" name="new_password" id="new_password" class="w-full rounded border px-3 py-2"
                    required>
                @error('new_password')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="new_password_confirmation" class="mb-1 block text-sm text-gray-700">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                    class="w-full rounded border px-3 py-2" required>
                @error('new_password_confirmation')
                    <div class="text-sm text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <a href="{{ route('user.profile.show', auth()->user()) }}"
                class="rounded bg-gray-100 px-4 py-2 text-gray-900 hover:bg-gray-200">
                Cancel</a>
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Update
                Password</button>
        </form>
    </div>
@endsection
