<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>B2B/B2C Gym Payments</title>
    <meta name="api-base-url" content="{{ config('app.url') }}">
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>

<body class="font-sans antialiased bg-slate-950 text-slate-50">
    <div id="app"></div>
</body>

</html>
