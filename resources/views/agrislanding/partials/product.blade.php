<!-- Section 2 - Product-->
<section id="product" class="py-20 bg-white">
    <div class="flex flex-col px-8 mx-auto space-y-12 max-w-7xl xl:px-12">
        <div class="relative">
            <h2 class="w-full text-3xl font-bold text-center sm:text-4xl md:text-5xl">الأنظمة التي يقدمها موقع المزارعون العرب</h2>
        </div>

        <div class="flex flex-col mb-8 animated fadeIn sm:flex-row">
            <div class="flex items-center mb-8 sm:w-1/2 md:w-5/12 {{1%2==0?"":"sm:order-last"}}">
                <img style="border-radius:14rem" class="shadow-xl" src="{{asset('images/logo/Farm_sys_sq.png')}}" alt="">
            </div>
            <div class="flex flex-col justify-center mt-5 mb-8 md:mt-0 sm:w-1/2 md:w-7/12 {{1%2==0?" sm:pl-16":" sm:pr-16"}}">
                <p class="mb-2 text-2xl font-bold leading-none text-right text-green-600 ">نظام المزارع</p>
                <h3 class="mt-2 text-2xl sm:text-right md:text-4xl">تطبيق ذكي وسهل الاستخدام</h3>
                <p class="mt-5 text-lg text-gray-700 text md:text-right">
                    قريبا في متجر التطبيقات
                    <span class="icon fa-google-play"></span>
                    <span class="icon fa-app-store-ios"></span>
                </p>
            </div>
        </div>

        <div class="flex flex-col mb-8 animated fadeIn sm:flex-row">
            <div class="flex items-center mb-8 sm:w-1/2 md:w-5/12 {{2%2==0?"":"sm:order-last"}}">
                <img style="border-radius:14rem" class="shadow-xl" src="{{asset('images/logo/Nursery_sys_sq.png')}}" alt="">
            </div>
            <div class="flex flex-col justify-center mt-5 mb-8 md:mt-0 sm:w-1/2 md:w-7/12 {{2%2==0?" sm:pr-16":" sm:pl-16"}}">
                <p class="mb-2 text-2xl font-bold leading-none text-right text-green-600 ">نظام المشاتل</p>
                <h3 class="mt-2 text-2xl sm:text-right md:text-4xl">نظام سحابي آمن وسهل الاستخدام</h3>
                <p class="mt-5 text-lg text-gray-700 text md:text-left">
                    <a  href="/login" style="width: 220px" class="flex items-center px-3 py-2 text-sm font-medium tracking-normal text-white transition duration-150 bg-green-400 rounded hover:bg-green-500 ease">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        استخدم ميزات نظام المشاتل السحابي
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>
