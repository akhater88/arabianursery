<x-guest-layout>
    <form method="POST" action="{{ isset($is_complete_action) && $is_complete_action ? route('nursery.store-complete-registration') : route('register') }}" dir="rtl">
        @csrf

        <!-- Nursery Name -->
        <div>
            <x-input-label for="nursery_name" value="اسم المشتل" />
            <x-text-input id="nursery_name" class="block mt-1 w-full" type="text" name="nursery_name" :value="old('nursery_name')" required autofocus autocomplete="nursery_name" />
            <x-input-error :messages="$errors->get('nursery_name')" class="mt-2" />
        </div>

        <!-- Nursery User's Name -->
        <div class="mt-4">
            <x-input-label for="nursery_user_name" value="اسم صاحب المشتل" />
            <x-text-input id="nursery_user_name" class="block mt-1 w-full" type="text" name="nursery_user_name" :value="$nursery_user_name ?? old('nursery_user_name')" required autofocus autocomplete="nursery_user_name" />
            <x-input-error :messages="$errors->get('nursery_user_name')" class="mt-2" />
        </div>

        <!-- Mobile Number -->
        <div class="mt-4">
            <x-input-label for="mobile_number" value="رقم الموبايل" />
            <x-text-input style="direction:ltr !important" id="mobile_number" class="block mt-1 w-full" type="text" name="mobile_number" :value="old('mobile_number')" required autofocus autocomplete="mobile_number" />
            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="الإيميل" />
            <x-text-input :disabled="isset($email)" style="direction:ltr !important" id="email" class="block mt-1 w-full" type="email" name="email" :value="$email ?? old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <input hidden id='lng' name="lng" value={{old('lng')}}>
            <input hidden id='lat' name="lat" value={{old('lat')}}>
            <x-input-label value="الموقع" />
            <div class="mt-4" id="map" style=" height: 400px;">
            </div>
            <x-input-error :messages="$errors->get('lng')" class="mt-2" />
            <x-input-error :messages="$errors->get('lat')" class="mt-2" />
        </div>

        <!-- Nursery Address -->
        <div class="mt-4">
            <x-input-label for="nursery_address" value="موقع المشتل" />
            <x-text-input id="nursery_address" class="block mt-1 w-full" type="text" name="nursery_address" :value="old('nursery_address')" required autofocus autocomplete="nursery_address" />
            <x-input-error :messages="$errors->get('nursery_address')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="كلمة السر" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="تأكيد كلمة السر" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            @unless($is_complete_action)
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('login') }}">
                    مسجّل بالفعل؟
                </a>
            @endunless

            <x-primary-button class="ms-4">
                {{ $is_complete_action ? 'حفظ' : 'فتح حساب' }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>

<script
    src="https://maps.googleapis.com/maps/api/js?key={{config('google.api_keys.maps_api')}}&callback=initMap&v=weekly"
    defer
></script>

<script>
    let map, infoWindow;

    const lngInput = document.getElementById("lng");
    const latInput = document.getElementById("lat");

    async function initMap() {
        let point = {
            lat: {{old('lat') ?? 31.95081055332211}},
            lng: {{old('lng') ?? 35.91395028637681}}
        };

        map = createGoogleMap(point);

        infoWindow = new google.maps.InfoWindow();

        const locationButton = createLocationButton();

        const draggableMarker = await createDraggableMarker(point);

        draggableMarker.addListener("dragend", () => {
            lngInput.value = draggableMarker.position.lng;
            latInput.value = draggableMarker.position.lat;
        });

        locationButton.addEventListener("click", (e) => {
            e.preventDefault();

            if (!navigator.geolocation) {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const point = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    draggableMarker.position = point;

                    lngInput.value = point.lng;
                    latInput.value = point.lat;

                    const latlng = point.lat + "," + point.lng;

                    setAddressInputFor(latlng);

                    map.setCenter(point);
                    map.setZoom(16);
                },
                () => {
                    handleLocationError(true, infoWindow, map.getCenter());
                }
            );
        });
    }

    function createGoogleMap(point) {
        return new google.maps.Map(document.getElementById("map"), {
            center: point,
            mapTypeControl: false,
            streetViewControl: false,
            zoom: {{ old('lat') && old('lng') ? 16 : 12 }},
            mapId: "4504f8b37365c3d0",
        });
    }

    function createLocationButton() {
        const locationButton = document.createElement("button");

        locationButton.innerHTML = "<i class='fas fa-crosshairs'></i>";
        locationButton.classList.add("custom-map-control-button");

        map.controls[google.maps.ControlPosition.LEFT_TOP].push(
            locationButton
        );

        return locationButton;
    }

    async function createDraggableMarker(pos) {
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        return new AdvancedMarkerElement({
            map,
            position: pos,
            gmpDraggable: true,
            title: "This marker is draggable.",
        });
    }

    function setAddressInputFor(latlng) {
        fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${latlng}&language=ar&result_type=neighborhood&key={{config('google.api_keys.geocoding_api')}}`)
            .then(res => res.json())
            .then(data => {
                const address = data.results[0]?.formatted_address;
                const addressInput = document.getElementById("nursery_address");

                addressInput.value = address ?? '';
            });
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
            browserHasGeolocation
                ? "Error: The Geolocation service failed."
                : "Error: Your browser doesn't support geolocation."
        );
        infoWindow.open(map);
    }

    window.initMap = initMap;
</script>
