<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Farmer Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/farmers.css') }}" rel="stylesheet">
</head>
<body>
    @include('farmers.partials.nav')
    @include('farmers.partials.messages')
    <div class="container">
        @yield('content')
    </div>
    <script src="{{ asset('js/farmers.js') }}"></script>
</body>
</html>