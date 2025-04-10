    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PPDB WALISONGO</title>

        @vite('resources/css/app.css')
        @vite('resources/css/navbar.css')
    </head>
    @section('header')
        @include('components.header')
    @endsection
    @section('navbar')
        @include('components.navbar')
    @endsection

    <body class="max-w-sm mx-auto relative min-h-screen">
        @yield('header')
        <div class="p-4 space-y-4 bg-[#f8f8f8] font-semibold pb-24 min-h-screen">
            @yield('content')
        </div>
        @yield('navbar')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
        @vite('resources/js/app.js')
    </body>
    <script>
        // Cek apakah token ada di local storage
        if (!localStorage.getItem('token')) {
            // Jika tidak ada, redirect ke halaman login
            window.location.href = '/login';
        }
    </script>
    
    </html>
