<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB WALISONGO</title>

    @vite(['resources/css/app.css', 'resources/css/navbar.css', 'resources/js/app.js'])
</head>
@section('header')
    @include('components.header')
@endsection

<body class="max-w-sm mx-auto relative min-h-screen">
    @yield('header')
    @yield('started')

    @stack('scripts')
    <div id="global-loading"
        class="fixed inset-0 z-[9999] bg-black bg-opacity-40 flex items-center justify-center hidden">
        <div class="w-12 h-12 border-4 border-t-transparent border-white rounded-full animate-spin"></div>
    </div>

</body>

</html>
