<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        نسيت كلمة المرور؟ لا مشكلة. فقط أخبرنا بعنوان البريد الإلكتروني الخاص بك، وسنرسل لك رابط إعادة تعيين كلمة المرور عبر البريد الإلكتروني، والذي سيسمح لك باختيار كلمة مرور جديدة.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email', $broker) }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="الإيميل" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus style="direction:ltr !important"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                أرسل رابط إعادة تعيين كلمة المرور عبر البريد الإلكتروني
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
