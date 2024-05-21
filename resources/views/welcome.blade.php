<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>المزارعون العرب</title>
    <link rel="stylesheet" href="{{ asset('custom') }}/css/tailwind.min.css">
    <!-- RTL and Commmon ( Phone ) -->
    @include('layouts.rtl')

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <script src="https://cdn.amplitude.com/libs/analytics-browser-2.7.3-min.js.gz"></script><script src="https://cdn.amplitude.com/libs/plugin-session-replay-browser-1.2.3-min.js.gz"></script><script src="https://cdn.amplitude.com/libs/plugin-autocapture-browser-0.9.0-min.js.gz"></script><script>window.amplitude.add(window.sessionReplay.plugin({sampleRate: 1})).promise.then(function() {window.amplitude.add(window.amplitudeAutocapturePlugin.plugin());window.amplitude.init('e1b607506616fb36070161d483b20845');});</script>
</head>
<body class="landing-page">
@include('agrislanding.partials.header')

@include('agrislanding.partials.product')

@include('agrislanding.partials.pricing')

@include('agrislanding.partials.footer')

<!-- AlpineJS Library -->
<script src="{{ asset('vendor') }}/alpine/alpine.js"></script>

<!--   Core JS Files   -->
<script src="{{ asset('vendor') }}/jquery/jquery.min.js" type="text/javascript"></script>


<!-- All in one -->
<script src="{{ asset('custom') }}/js/js.js?id={{ config('config.version')}}s"></script>


</body>
</html>
