<!DOCTYPE html>
<html>
<head>
   <title>Glifoo - @yield('title', 'Adminsitrativo')</title>
    <link rel="icon" href="{{ asset('./img/logos/Boton.ico') }}">
    @livewireStyles
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>