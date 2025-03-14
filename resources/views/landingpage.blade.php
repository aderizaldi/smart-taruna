<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ $title ?? 'Laravel' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @fluxAppearance --}}
</head>

<body class="h-dvh bg-white dark:bg-zinc-800">
    <flux:container class="flex justify-between mt-10">
        <img src="{{ asset('assets/primamarta_logo.png') }}" alt="" width="150">

        <span class="flex items-center text-[#20327A] font-bold">Welcome!<flux:icon.user-circle variant="solid"
                class="size-10 ms-3"></flux:icon.user-circle></span>
    </flux:container>

    <flux:container class="lg:flex justify-between items-center mt-10 w-full">
        <div class="columns-md text-balance p-12">
            <p class="text-[#20327A] text-6xl font-extrabold">SMART TARUNA</p>
            <div class="lg:w-1/2 my-5">
                <p class="text-[#20327A] text-2xl font-light">Lorem ipsum dolor, sit amet consectetur adipisicing
                    elit.
                    Temporibus ex earum cupiditate, quas optio animi neque facere odio! Aliquid facere fugit delectus
                    expedita eum quo tempore, consectetur vel nobis unde!</p>
            </div>
            <flux:button icon-trailing="arrow-long-right"
                class="rounded-lg w-30 bg-[#20327A]! text-white! hover:bg-[#4054A5]! font-bold!">
                Login
            </flux:button>
        </div>
        <div class="columns-2xl text-balance p-12">
            <img src="{{ asset('assets/smart_taruna_logo.png') }}" class="w-full object-cover" alt="">
        </div>
    </flux:container>
    @fluxScripts
</body>

</html>
