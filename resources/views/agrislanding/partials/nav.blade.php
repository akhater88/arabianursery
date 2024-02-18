
                <nav class="flex-col items-center justify-center hidden h-full space-y-3 bg-white md:justify-end md:bg-transparent md:space-x-5 md:space-y-0 md:relative md:flex md:flex-row" :class="{'flex fixed top-0 left-0 w-full z-40': showMenu, 'hidden': !showMenu }">
{{--                    <a href="#" class="relative text-lg font-medium tracking-wide text-green-400 transition duration-150 ease-out md:text-sm md:text-white" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">--}}
{{--                        <span class="block">{{ __('agris.home') }}</span>--}}
{{--                        <span class="absolute bottom-0 left-0 inline-block w-full h-1 -mb-1 overflow-hidden">--}}
{{--                            <span x-show="hover" class="absolute inset-0 inline-block w-full h-1 h-full transform border-t-2 border-green-400" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300" x-transition:leave-start="scale-100" x-transition:leave-end="scale-0" style="display: none;"></span>--}}
{{--                        </span>--}}
{{--                    </a>--}}
{{--                    <a href="/#product" class="relative text-lg font-medium tracking-wide text-green-400 transition duration-150 ease-out md:text-white md:text-sm" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">--}}
{{--                        <span class="block">{{ __('agris.product') }}</span>--}}
{{--                        <span class="absolute bottom-0 left-0 inline-block w-full h-1 -mb-1 overflow-hidden">--}}
{{--                            <span x-show="hover" class="absolute inset-0 inline-block w-full h-1 h-full transform border-t-2 border-green-400" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300" x-transition:leave-start="scale-100" x-transition:leave-end="scale-0" style="display: none;"></span>--}}
{{--                        </span>--}}
{{--                    </a>--}}
{{--                   --}}
{{--                   --}}
{{--                    <a href="/#pricing" class="relative text-lg font-medium tracking-wide text-green-400 transition duration-150 ease-out md:text-white md:text-sm" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">--}}
{{--                        <span class="block">{{ __('agris.pricing') }}</span>--}}
{{--                        <span class="absolute bottom-0 left-0 inline-block w-full h-1 -mb-1 overflow-hidden">--}}
{{--                            <span x-show="hover" class="absolute inset-0 inline-block w-full h-1 h-full transform border-t-2 border-green-400" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300" x-transition:leave-start="scale-100" x-transition:leave-end="scale-0" style="display: none;"></span>--}}
{{--                        </span>--}}
{{--                    </a>--}}
{{--                   --}}
{{--                    <a href="/#testimonials" class="relative text-lg font-medium tracking-wide text-green-400 transition duration-150 ease-out md:text-sm md:text-white" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">--}}
{{--                        <span class="block">{{ __('agris.testimonials') }}</span>--}}
{{--                        <span class="absolute bottom-0 left-0 inline-block w-full h-1 -mb-1 overflow-hidden">--}}
{{--                            <span x-show="hover" class="absolute inset-0 inline-block w-full h-1 h-full transform border-t-2 border-green-400" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300" x-transition:leave-start="scale-100" x-transition:leave-end="scale-0" style="display: none;"></span>--}}
{{--                        </span>--}}
{{--                    </a>--}}
{{--                    <a href="/#faq" class="relative text-lg font-medium tracking-wide text-green-400 transition duration-150 ease-out md:text-sm md:text-white" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">--}}
{{--                        <span class="block">{{ __('agris.faq') }}</span>--}}
{{--                        <span class="absolute bottom-0 left-0 inline-block w-full h-1 -mb-1 overflow-hidden">--}}
{{--                            <span x-show="hover" class="absolute inset-0 inline-block w-full h-1 h-full transform border-t-2 border-green-400" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300" x-transition:leave-start="scale-100" x-transition:leave-end="scale-0" style="display: none;"></span>--}}
{{--                        </span>--}}
{{--                    </a>--}}
                    @if(isset($availableLanguages)&&count($availableLanguages)>1&&isset($locale))
                        <!--<li class="nav-item dropdown">-->
                        <!--    <a href="#" class="nav-link" data-toggle="dropdown" role="button">-->
                        <!--        <i class="ni ni-world-2"></i>-->
                        <!--        @foreach ($availableLanguages as $short => $lang)-->
                        <!--            @if(strtolower($short) == strtolower($locale))<span class="nav-link-inner--text">{{ __($lang) }}</span>@endif-->
                        <!--        @endforeach-->
                        <!--    </a>-->
                        <!--    <div class="dropdown-menu">-->
                        <!--        @foreach ($availableLanguages as $short => $lang)-->
                        <!--            <a href="{{ route('lang.switch')}}?lang={{$short}}" class="dropdown-item">-->
                                        <!--<img src="{{ asset('images') }}/icons/flags/{{ strtoupper($short)}}.png" /> -->
                        <!--                {{ __($lang) }}</a>-->
                        <!--        @endforeach-->
                        <!--    </div>-->
                        <!--</li>-->




                    @endif
                        <a href="/login" class="flex items-center px-3 py-2 text-sm font-medium tracking-normal text-white transition duration-150 bg-green-400 rounded hover:bg-green-500 ease">
                            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            استخدم ميزات الموقع
                        </a>


                </nav>
