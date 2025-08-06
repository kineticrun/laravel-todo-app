<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>{{ $title ?? 'Website' }}</title>
</head>


<body class="min-h-screen bg-amber-100 text-slate-800 md:p-6 font-sans">
    <div class="container mx-auto w-full md:w-[50%]">
        <x-navigation />
        <main class="">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
