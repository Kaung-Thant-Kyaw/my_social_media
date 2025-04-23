<nav class="bg-white shadow">
    <div class="container mx-auto flex items-center justify-between px-4 py-3">
        <!-- Logo/Brand -->
        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">SocialApp</a>

        <!-- Search Bar (Mobile) - Hidden on desktop -->
        <div class="md:hidden">
            <button class="text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <div class="hidden items-center space-x-6 md:flex">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
            </a>
            <!-- Add more navigation icons as needed -->
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative">
            <button class="flex items-center space-x-2 focus:outline-none" id="user-menu-button">
                <img src="{{ asset('profile_pictures/' . auth()->user()->avatar) ?? asset('default_user.jpg') }}"
                    alt="Profile" class="h-8 w-8 rounded-full">
                <span class="hidden md:inline-block">{{ auth()->user()->name }}</span>
            </button>

            <!-- Dropdown menu -->
            <div class="absolute right-0 z-10 mt-2 hidden w-48 rounded-md bg-white py-1 shadow-lg" id="user-menu">
                <a href="{{ route('user.profile.show', auth()->user()) }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Profile</a>
                {{-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a> --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">Sign
                        out</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    // Simple dropdown toggle
    document.getElementById('user-menu-button').addEventListener('click', function() {
        document.getElementById('user-menu').classList.toggle('hidden');
    });
</script>
