<!-- Section 1 - Header -->
<section class="relative w-full bg-top bg-cover md:bg-center" x-data="{ showMenu: false }" style="background-image:url('/agris/bg.jpeg')">



    <div class="absolute inset-0 w-full h-full bg-gray-900 opacity-25"></div>
    <div class="absolute inset-0 w-full h-64 opacity-50 bg-gradient-to-b from-black to-transparent"></div>
    <div class="relative flex items-center justify-between w-full h-20 px-8">

        <a href="/" class="relative flex items-center h-full pr-6 text-2xl font-extrabold text-white">المزارعون العرب<span class="text-green-400">.</span></a>
        @include('agrislanding.partials.nav')

        <!-- Mobile Nav  -->
        <nav class="fixed top-0 right-0 z-30 z-50 flex w-10 h-10 mt-4 mr-4 md:hidden">
            <button @click="showMenu = !showMenu" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-white hover:bg-opacity-25 focus:outline-none">
                <svg class="w-5 h-5 text-gray-200 fill-current" x-show="!showMenu" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path></svg>
                <svg class="w-5 h-5 text-gray-500" x-show="showMenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </nav>
        <!-- End Mobile Nav -->
    </div>
    <div class="relative z-10 max-w-6xl px-10 py-40 mx-auto">
        <div class="flex flex-col items-center h-full lg:flex-row">
            <div class="flex flex-col items-center justify-center w-full h-full lg:w-2/3 lg:items-start">
                <a href="/#product" class="flex items-center px-3 py-2 text-sm font-medium tracking-normal text-white transition duration-150 bg-green-400 rounded hover:bg-green-500 ease">
                    <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    انضم الينا
                </a>
                <h1 class="font-extrabold tracking-tight text-center text-white text-7xl lg:text-left xl:pr-32">
                    لننهض بقطاع الزراعة

                </h1>

            </div>

        </div>
    </div>


</section>
