<!-- Top Notification Bar -->
@if (session('status'))
<a class="block pt-16 pb-6 bg-blue-900 opacity-100 md:h-16 md:pt-0 md:pb-0">
    <span class="relative flex items-center justify-center h-full max-w-6xl pl-10 pr-20 mx-auto leading-tight text-left md:text-center">
      <span class="text-white">{{ __("".session('status')) }}</span>
    </span>
</a>
@endif
  
<!-- Section 1 - Header -->
<section class="relative w-full bg-top bg-cover md:bg-center" x-data="{ showMenu: false }" style="background-image:url('/agris/bg.jpeg')">
    
    

    <div class="absolute inset-0 w-full h-full bg-gray-900 opacity-25"></div>
    <div class="absolute inset-0 w-full h-64 opacity-50 bg-gradient-to-b from-black to-transparent"></div>
    <div class="relative flex items-center justify-between w-full h-20 px-8">

        <a href="/" class="relative flex items-center h-full pr-6 text-2xl font-extrabold text-white">{{ strtolower(config('global.site_name','AgriS')) }}<span class="text-green-400">.</span></a>
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
   

</section>


<!-- Section 1 -->
<section class="px-2 py-10 bg-white md:px-0">
    <div class="container items-center max-w-6xl px-8 mx-auto xl:px-5">
      <div class="flex flex-wrap items-center sm:-mx-3">
        <div class="w-full  @if (strlen($note->image)>3) md:w-1/2  @else md:w-1/1  @endif md:px-3">
          <div class="w-full pb-6 space-y-6 sm:max-w-md lg:max-w-lg md:space-y-4 lg:space-y-8 xl:space-y-9 sm:pr-5 lg:pr-0 md:pb-0">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-4xl lg:text-5xl xl:text-6xl">
              <span class="block xl:inline">{{ $note->title }}</span><br />
             
            </h1>
            <p class="mx-auto text-base text-gray-500 sm:max-w-md lg:text-xl md:max-w-3xl">{{ $note->description }}</p>
            <div class="relative flex flex-col sm:flex-row sm:space-x-4">
             
              <a target='_blank' href="https://www.google.com/maps/search/?api=1&t=satellite&query={{$note->lat}},{{$note->lng}}" class="flex items-center px-6 py-3 text-gray-500 bg-gray-100 rounded-md hover:bg-gray-200 hover:text-gray-600">
               {{ __('Show on map' )}}
              </a>
            </div>
          </div>
        </div>
        @if (strlen($note->image)>3)
            <div class="w-full md:w-1/2">
                <div class="w-full h-auto overflow-hidden rounded-md shadow-xl sm:rounded-xl">
                    <img src="{{ $note->image }}">
                </div>
            </div>
        @endif
        
      </div>
    </div>
  </section>

  <section class=" bg-white md:px-0">
    <div class="container items-center max-w-6xl px-8 mx-auto xl:px-5">
        <div id="disqus_thread" class="mt-20 flex flex-col mx-auto max-w-7xl lg:flex-row"></div>
    </div>
  </section>
  
