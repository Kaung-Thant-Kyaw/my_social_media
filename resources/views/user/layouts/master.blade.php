<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Social Media</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        {{-- jquery cdn link --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        {{-- Font awesome cdn link --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>

    <body class="bg-gray-100">
        @yield('content')
        @stack('styles')
        @stack('scripts')
    </body>

</html>
