<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portfolio</title>
        <link rel="icon" type="image/png" href="{{ asset('images/gj.png') }}">
        <!-- Google Font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Elms+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@yield('content')
<script src="{{ asset('js/main.js') }}"></script>
@stack('scripts')
{{--@php($footerYear = optional(\App\Models\Setting::find('footeryear'))->value ?? date('Y'))--}}

<footer class="footer">
    <div class="footer-links">
        <a href="{{ url('/') }}#home">Home</a>
        <a href="{{ url('/') }}#about">About</a>
        <a href="{{ url('/') }}#projects">Projects</a>
        <a href="{{ url('/') }}#skills">Skills</a>
        <a href="{{ url('/') }}#contact">Contact</a>
    </div>

    <p class="copyright">
        &copy; {{ $footerYear }} Gokulraju All Rights Reserved.
    </p>
</footer>



</body>
</html>
