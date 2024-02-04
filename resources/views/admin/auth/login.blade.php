<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('admin.login') }}">
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

        <div class="flex items-center mt-1 mb-3">

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

                <div class="text-center">

                </div>
            </div>
        </div>

    </form>
</x-guest-layout>
