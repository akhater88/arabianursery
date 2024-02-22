
<!-- Section 1 -->
<section id="pricing" class="relative py-20 bg-gray-50">
    <div class="relative px-10 mx-auto max-w-7xl lg:px-16">
        <div class="max-w-3xl mx-auto mb-16 text-center lg:mb-24">
            <h2 class="mt-3 mb-5 text-5xl font-medium font-heading">أسعار أستخدام الانظمة</h2>
            <p class="mb-5 text-xl text-gray-400">خدمة مجانية للمزارعين</p>
        </div>
        <?php
            $gridCount=2;
        ?>
        <div class="md:grid md:grid-cols-2 lg:grid-cols-{{$gridCount}} gap-x-10">
            <?php
                $colors=['border-blue-400','border-green-400','border-yellow-300','border-red-300','border-blue-400','border-green-400','border-yellow-300','border-red-300']
            ?>




                <div class="col-span-1 p-10 bg-white border-t-4 border-green-400">
                    <div class="flex flex-col pb-8 border-b border-gray-200">
                        <h3 class="flex items-center text-5xl font-semibold text-green-400">د.أ ٠.٠<span class="inline-block pl-2 mt-2 text-xs text-gray-400 text-normal">/ شهريا </span></h3>
                        <h4 class="mt-5 text-2xl font-medium">تطبيق المزارع</h4>
                        <p class="mt-4 text-gray-500">تطبيق ذكي ومجاني للمزارع</p>
                    </div>
                    <ul class="px-3 pt-8 space-y-3">
                        <li class="flex font-medium text-gray-500">
                            <svg class="w-6 h-6 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            إرشادات زراعية
                        </li>

                        <li class="flex font-medium text-gray-500">
                            <svg class="w-6 h-6 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            تنبهات بخصوص حالة الطقس
                        </li>

                        <li class="flex font-medium text-gray-500">
                            <svg class="w-6 h-6 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            متابعة الاشتال في المشاتل
                        </li>
                    </ul>

                </div>


                <div class="col-span-1 p-10 bg-white border-t-4 border-green-400">
                    <div class="flex flex-col pb-8 border-b border-gray-200">
                        <h3 class="flex items-center text-5xl font-semibold text-green-400">د.أ ٠.٠<span class="inline-block pl-2 mt-2 text-xs text-gray-400 text-normal">/ شهريا </span></h3>
                        <h4 class="mt-5 text-2xl font-medium">نظام المشتل</h4>
                        <p class="mt-4 text-gray-500">نظام سحابي يساعد بادارة المشتل</p>
                    </div>
                    <ul class="px-3 pt-8 space-y-3">
                        <li class="flex font-medium text-gray-500">
                            <svg class="w-6 h-6 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            إدارة خدمات التشتيل
                        </li>

                        <li class="flex font-medium text-gray-500">
                            <svg class="w-6 h-6 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            إدارة خدمات مخزن المشتل
                        </li>

                        <li class="flex font-medium text-gray-500">
                            <svg class="w-6 h-6 mr-1.5 text-green-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            تسويق أشتال خاصة المشتل
                        </li>
                    </ul>

                </div>


        </div>

    </div>
</section>


<!-- Section pricing
<section id="pricing" class="py-20 bg-gray-100">
    <div class="flex flex-col max-w-6xl px-12 mx-auto lg:px-8">
        <h1 class="max-w-md text-4xl font-extrabold text-gray-900 sm:mx-auto lg:max-w-none lg:text-5xl sm:text-center">Pricing Plans</h1>
        <p class="max-w-md mx-auto mt-5 text-lg text-gray-500 lg:max-w-none lg:text-xl sm:text-center">Everything you need to help you succeed. Simple transparent pricing to fit businesses of any size.</p>
        <div class="flex flex-wrap mx-auto mt-12 max-w-7xl">
            <div class="w-full max-w-md px-0 mx-auto mb-6 lg:w-1/2 lg:px-3 lg:mb-0">
                <div class="flex flex-col h-full mb-10 bg-white border-2 border-gray-200 rounded-lg shadow-sm sm:mb-0">
                    <div class="px-10 pt-10">
                        <span class="px-4 py-1 text-base font-medium text-indigo-700 bg-indigo-100 rounded-full text-uppercase">
                            Lite
                        </span>
                    </div>

                    <div class="px-10 mt-5">
                        <span class="text-5xl font-bold">$0</span>
                        <span class="text-xl font-bold text-gray-500">/mo</span>
                    </div>

                    <div class="px-10 pb-10 mt-3">
                        <p class="text-lg leading-7 text-gray-500">Free limited features for individuals</p>
                    </div>

                    <div class="p-10 mt-auto bg-gray-100 rounded-b-lg">
                        <ul class="flex flex-col">
                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Manage fields
                                    </span>
                                </span>
                            </li>

                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Track expenses
                                    </span>
                                </span>
                            </li>

                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Scouting and notes
                                    </span>
                                </span>
                            </li>

                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Weather reports
                                    </span>
                                </span>
                            </li>
                        </ul>

                        <div class="mt-8">
                            <a href="#_" class="inline-flex items-center justify-center w-full px-4 py-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full max-w-md px-0 mx-auto mb-6 lg:w-1/2 lg:px-3 lg:mb-0">
                <div class="flex flex-col h-full mb-10 bg-white border-2 border-gray-200 rounded-lg shadow-sm ring-4 ring-opacity-25 ring-indigo-300 sm:mb-0">
                    <div class="px-10 pt-10">
                        <span class="px-4 py-1 text-base font-medium text-indigo-700 bg-indigo-100 rounded-full leading-nine text-uppercase">
                            Pro
                        </span>
                    </div>

                    <div class="px-10 mt-5">
                        <span class="text-5xl font-bold">$15</span>
                        <span class="text-xl font-bold text-gray-500">/mo</span>
                    </div>

                    <div class="px-10 pb-10 mt-3">
                        <p class="text-lg leading-7 text-gray-500">Premium features for individuals and companies</p>
                    </div>

                    <div class="p-10 mt-auto bg-gray-100 rounded-b-lg">
                        <ul class="flex flex-col">
                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Manage fields
                                    </span>
                                </span>
                            </li>

                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Track expenses
                                    </span>
                                </span>
                            </li>

                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Scouting and notes
                                    </span>
                                </span>
                            </li>

                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Weather reports
                                    </span>
                                </span>
                            </li>

                            <li class="mt-1">
                                <span class="flex items-center text-green-500">
                                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z"></path>
                                    </svg>

                                    <span class="text-gray-700">
                                        Sell online
                                    </span>
                                </span>
                            </li>
                        </ul>

                        <div class="mt-8">
                            <a href="#_" class="inline-flex items-center justify-center w-full px-4 py-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                Get Started
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
-->
