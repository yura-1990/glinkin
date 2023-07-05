<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cabinet project swagger documentation</title>
    <link rel="stylesheet" href="{{ asset('/build/assets/swagger-23d0d482.css') }}">
    <script src="{{assert('/build/assets/swagger-9cea0d1b.js')}}"></script>
</head>
<body>
<div id="swagger-api"></div>
@vite('resources/js/swagger.js')
</body>
</html>
