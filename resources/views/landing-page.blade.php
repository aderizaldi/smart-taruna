<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>SMART TARUNA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance

    <style>
        body {
            font-family: 'Poppins',
                sans-serif;
        }

        .swiper-pagination-bullet-active {
            background-color: #20327A !important;
        }
    </style>
</head>


<body class="h-dvh bg-white dark:bg-zinc-800">

    <flux:container class="flex justify-between mt-10">
        <img src="{{ asset('assets/primamarta_logo.png') }}" alt="primamarta_logo_nav" width="150"
            class="object-fill">
        <span class="flex items-center
                text-[#20327A] font-bold">Welcome!<flux:icon.user-circle
                variant="solid" class="size-10 ms-2"></flux:icon.user-circle></span>
    </flux:container>

    <flux:container class="lg:flex justify-between items-center mt-10 w-full bg-cover bg-center"
        style="background-image: url('{{ asset('assets/indonesia.png') }}');">
        <div class="columns-md text-balance p-12">
            <p class="text-[#20327A] text-6xl font-extrabold">SMART TARUNA</p>
            <div class="lg:w-1/2 my-5">
                <p class="text-[#20327A] text-2xl font-light">Lorem ipsum dolor, sit amet consectetur adipisicing
                    elit.
                    Temporibus ex earum cupiditate, quas optio animi neque facere odio! Aliquid facere fugit
                    delectus
                    expedita eum quo tempore, consectetur vel nobis unde!</p>
            </div>
            <flux:button icon-trailing="arrow-long-right" :href="route('login')" wire:navigate
                class="rounded-lg w-30 bg-[#20327A]! text-white! hover:bg-[#4054A5]! font-bold! border-none!">
                Login
            </flux:button>
        </div>
        <div class="columns-2xl text-balance p-12">
            <img src="{{ asset('assets/smart_taruna_logo.png') }}" class="w-full object-cover" alt="">
        </div>
    </flux:container>

    <div class="bg-repeat-round bg-cover pt-1" style="background-image: url('{{ asset('assets/Simple Shiny.svg') }}');">
        <flux:container class="flex justify-center mt-10 w-full">
            <flux:container class="bg-[#20327A]! mx-30! md:mx-50! text-white! py-6 rounded-full">
                <div class="grid md:grid-cols-3 gap-3">
                    <div class="flex flex-col items-center px-10">
                        <div class="bg-[#6E7AA9] rounded-full p-2">
                            <flux:icon.clipboard-document-list class="size-5"></flux:icon.clipboard-document-list>
                        </div>
                        <p class="text-3xl font-bold mt-2 text-[#EC0E0F]">190+</p>
                        <p class="text-center">Try Out Tersedia</p>
                    </div>
                    <div class="flex flex-col items-center px-10">
                        <div class="bg-[#6E7AA9] rounded-full p-2">
                            <flux:icon.academic-cap class="size-5"></flux:icon.academic-cap>
                        </div>
                        <p class="text-3xl font-bold mt-2 text-[#EC0E0F]">120K</p>
                        <p class="text-center">Siswa Lulus Kedinasan & PNS</p>
                    </div>
                    <div class="flex flex-col items-center px-10">
                        <div class="bg-[#6E7AA9] rounded-full p-2">
                            <flux:icon.users class="size-5 bg-[#6E7AA9]"></flux:icon.users>
                        </div>
                        <p class="text-3xl font-bold mt-2 text-[#EC0E0F]">58</p>
                        <p class="text-center">Siswa Mendaftar Setiap Tahunnya</p>
                    </div>
                </div>
            </flux:container>
        </flux:container>

        <p
            class="text-[#20327A] text-2xl md:text-5xl font-semibold text-center mt-15 md:mt-25 underline underline-offset-[8px] md:underline-offset-[15px]">
            Our Gallery
        </p>

        <div class="relative w-full max-w-5xl mx-auto mt-5 md:mt-15 flex justify-center items-center">
            <flux:icon.chevron-left
                class="size-10 swiper-button-prev-custom text-[#20327A] me-3"></flux:icon.chevron-left>
            <!-- Wrapper Swiper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <!-- Slide 1 -->
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/1.png') }}" class="w-full rounded-lg" alt="Slide 1">
                    </div>
                    <!-- Slide 2 -->
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/2.png') }}" class="w-full rounded-lg" alt="Slide 2">
                    </div>
                    <!-- Slide 3 -->
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/3.png') }}" class="w-full rounded-lg" alt="Slide 3">
                    </div>
                    <!-- Slide 4 -->
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/4.png') }}" class="w-full rounded-lg" alt="Slide 4">
                    </div>
                    <!-- Slide 5 -->
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/5.png') }}" class="w-full rounded-lg" alt="Slide 5">
                    </div>
                </div>
                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <flux:icon.chevron-right
                class="size-10 swiper-button-next-custom text-[#20327A] ms-3"></flux:icon.chevron-right>
        </div>

        <flux:container class="mt-30 w-full relative">
            <div class="grid md:grid-cols-3 gap-10 md:gap-60">
                <div class="flex flex-col items-start">
                    <img src="{{ asset('assets/primamarta_logo.png') }}" alt="primamarta_logo_footer" width="200"
                        class="object-fill">
                    <div class="flex w-full items-center gap-1">
                        <flux:icon.map-pin class="text-[#20327A]">
                        </flux:icon.map-pin>
                        <p class="text-[#20327A] text-base font-semibold">Jl.R.E. Martadinata No.158</p>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-y-1">
                    <p class="font-bold text-xl text-[#20327A] mb-2">CONTACT US</p>
                    <div class="flex md:justify-start gap-1">
                        <flux:icon.envelope class="text-[#20327A]">
                        </flux:icon.envelope>
                        <p class="text-[#20327A] text-base font-semibold">smarttaruna@gmail.com</p>
                    </div>
                    <div class="flex md:justify-start gap-1">
                        <flux:icon.phone class="text-[#20327A]">
                        </flux:icon.phone>
                        <p class="text-[#20327A] text-base font-semibold">+62 22 423 0000</p>
                    </div>
                </div>
                <div class="flex flex-col items-start gap-y-1">
                    <p class="font-bold text-xl text-[#20327A] mb-2">SOCIAL MEDIA</p>
                    <div class="flex md:justify-start gap-1">
                        <flux:icon.instagram>
                        </flux:icon.instagram>
                        <p class="text-[#20327A] text-base font-semibold">smarttarunaeducation</p>
                    </div>
                    <div class="flex md:justify-start gap-1">
                        <flux:icon.x>
                        </flux:icon.x>
                        <p class="text-[#20327A] text-base font-semibold">smarttarunaedu</p>
                    </div>
                    <div class="flex md:justify-start gap-1">
                        <flux:icon.facebook>
                        </flux:icon.facebook>
                        <p class="text-[#20327A] text-base font-semibold">smarttarunaedu</p>
                    </div>
                    <div class="flex md:justify-start gap-1">
                        <flux:icon.youtube>
                        </flux:icon.youtube>
                        <p class="text-[#20327A] text-base font-semibold">smarttarunaedu</p>
                    </div>
                </div>
            </div>
        </flux:container>

        <flux:container class="h-20 flex justify-center items-center text-gray-400">
            <p class="text-xs">© Ngoding House {{ date('Y') }}</p>
        </flux:container>
    </div>

    @fluxScripts
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 3, // 3 gambar per slide
            spaceBetween: 20, // Jarak antar gambar
            loop: true, // Agar carousel berulang
            navigation: {
                nextEl: ".swiper-button-next-custom",
                prevEl: ".swiper-button-prev-custom",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                240: {
                    slidesPerView: 1, // 1 gambar di layar kecil
                },
                640: {
                    slidesPerView: 1, // 1 gambar di layar kecil
                },
                768: {
                    slidesPerView: 2, // 2 gambar di tablet
                },
                1024: {
                    slidesPerView: 3, // 3 gambar di layar besar
                }
            }
        });
    </script>
</body>

</html>
