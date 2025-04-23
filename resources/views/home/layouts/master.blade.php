<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Social Media</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{-- Font awesome cdn link --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body class="bg-gray-100">
        @include('partials.navbar')
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col gap-6 md:flex-row">
                <div class="w-full md:w-1/4 lg:w-1/5">
                    @yield('left-sidebar')
                </div>
                <div class="lg:2-3/5 w-full md:w-2/4">
                    @yield('content')
                </div>
                <div class="w-full md:w-1/4 lg:w-1/5">
                    @yield('right-sidebar')
                </div>
            </div>
        </div>
    </body>

</html>
