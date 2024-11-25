<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Movie Recommendation Project</title>

    <script>
        window.movies = JSON.parse(`{!! $movies !!}`);
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-300">

    <div id="app" class="mx-auto mt-12 bg-gray-100 p-4 mb-8 rounded-md" style="width: 320px;">
        <home-page></home-page>
    </div>

</body>
</html>
