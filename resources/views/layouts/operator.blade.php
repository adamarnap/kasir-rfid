<!DOCTYPE html>
<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}"">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $_site->title ?? config('app.name', 'Laravel') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/img/favicon.png" rel="icon">
    <link href="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link rel="stylesheet" href="{{ asset('storage/default/style.css') }}">
    @yield('style')
    @stack('style')
    @laravelPWA
</head>
<body>
    @include('layouts.operator.header')

    @include('layouts.operator.sidebar')
    
    <main id="main" class="main">
        
        <div class="pagetitle">
            <h1>@yield('title')</h1>
            <nav>
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div><!-- End Page Title -->
        
        <section class="section dashboard">
            <div class="row">
                @yield('content')
            </div>
        </section>
    </main>
    
    @include('layouts.operator.footer')

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <script src="https://bootstrapmade.com/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="https://bootstrapmade.com/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://bootstrapmade.com/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="https://bootstrapmade.com/assets/vendor/echarts/echarts.min.js"></script>
    <script src="https://bootstrapmade.com/assets/vendor/quill/quill.min.js"></script>
    <script src="https://bootstrapmade.com/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="https://bootstrapmade.com/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="https://bootstrapmade.com/assets/vendor/php-email-form/validate.js"></script>
    <!-- Template Main JS File -->
    <script src="https://bootstrapmade.com/demo/templates/NiceAdmin/assets/js/main.js"></script>
    @yield('script')
    @stack('script')
</body>
</html>