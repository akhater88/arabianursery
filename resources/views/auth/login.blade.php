<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="الإيميل" />
            <x-text-input style="direction:ltr !important" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="كلمة السر" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            style="direction:ltr !important"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-10 offset-md-1">
                <button class="btn btn-dark btn-block rounded-pill">
                    تسجيل دخول
                </button>
            </div>
        </div>

        <hr class="mt-3">

        <div class="form-group row mb-0">
            <div class="col-md-10 offset-md-1">
                <a href="{{ route('login.social', 'google') }}"  class="btn btn-danger btn-block mt-3 rounded-pill">
                    <strong>قم بالتسجيل باستخدام  جوجل <i class="fab fa-google"></i></strong>
                </a>

                <a href="{{ route('login.social', 'facebook') }}"  class="btn btn-primary btn-block mt-3 mb-2 rounded-pill">
                    <strong>قم بالتسجيل باستخدام الفيسبوك <i class="fab fa-facebook"></i></strong>
                </a>

                <a href="{{ route('login.social', 'twitter') }}"  class="btn btn-info btn-block mt-3 mb-2 rounded-pill">
                    <strong>قم بالتسجيل باستخدام تويتر <i class="fab fa-twitter"></i></strong>
                </a>

                <div class="text-center">
                    <span>ليس لديك حساب؟ <a href="{{route('register')}}">تسجيل</a></span>
                </div>
            </div>
        </div>

    </form>
</x-guest-layout>
