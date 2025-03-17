<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="relative h-full flex-col justify-between p-10 text-white lg:flex dark:border-r dark:border-neutral-800"
            style="background-image: url('{{ asset('assets/Simple Shiny.svg') }}');">
            <a href="{{ route('home') }}" class="relative z-20 flex justify-between items-center text-lg font-medium"
                wire:navigate>
                <span class="flex h-30 w-30 items-center justify-center rounded-md">
                    <img src="{{ asset('assets/primamarta_logo.png') }}" alt="">
                </span>
                <span class="text-[#20327A]! font-bold!">SMART TARUNA</span>
            </a>

            <flux:container class="relative z-20 flex justify-center">
                <img src="{{ asset('assets/smart_taruna_logo.png') }}" alt="" width="200"
                    class="object-cover" />
            </flux:container>

            @php
                [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
            @endphp

            <div class="relative z-20 flex justify-between items-center">
                <blockquote class="space-y-2">
                    <flux:heading size="lg" class="text-[#20327A]!">&ldquo;{{ trim($message) }}&rdquo;
                    </flux:heading>
                    <footer>
                        <flux:heading class="text-[#20327A]!">{{ trim($author) }}</flux:heading>
                    </footer>
                </blockquote>
                <flux:dropdown x-data>
                    <flux:button variant="subtle" square class="group" aria-label="Preferred color scheme">
                        <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini"
                            class="text-zinc-500 dark:text-white" />
                        <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini"
                            class="text-zinc-500 dark:text-white" />
                        <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" />
                        <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" />
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Light</flux:menu.item>
                        <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Dark</flux:menu.item>
                        <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">System
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>
