<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">

    <div class="container mx-auto py-8">
        @yield('content')
    </div>
    @vite(['resources/js/app.js'])
</body>
</html>
