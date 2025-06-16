<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title') | {{ $prefs_composer['title'] }}</title>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="{{ $prefs_composer['title'] }}">
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicon (Optional) -->
    <link rel="icon" href="{{ Storage::url($prefs_composer['favicon']) }}" type="image/x-icon">

    <!-- Styles -->
    @include('layouts.custom-template.css-layouts.maincss')

    <!-- Vite -->
    @vite(['resources/js/app.js'])
</head>

<body
    class="{{ View::hasSection('auth-content') ? 'login-page bg-body-secondary' : 'layout-fixed sidebar-expand-lg bg-body-tertiary' }}">
    @if (View::hasSection('auth-content'))
        {{-- Auth Content --}}
        @yield('auth-content')
    @else
        {{-- App Wrapper --}}
        <div class="app-wrapper">
            @include('layouts.custom-template.topbar.topbar')
            @include('layouts.custom-template.sidebar.sidebar')

            <main class="app-main">
                <div class="app-content-header">
                    {{-- Start Breadcrumbs --}}
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="mb-0">@yield('title')</h3>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-end">
                                    @yield('breadcrumb')
                                </ol>
                            </div>
                        </div>
                    </div>
                    {{-- End Breadcrumbs --}}
                </div>

                {{-- Main Content --}}
                <!--begin::App Content-->
                <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        {{-- Start Alert --}}
                        <x-auto-alert />
                        {{-- End Alert --}}
                        {{-- Start Content --}}
                        @yield('content')
                        {{-- End Content --}}
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::App Content-->
                {{-- End Main Content --}}
            </main>

            <footer class="app-footer">
                @include('layouts.custom-template.footer.footer')
            </footer>
        </div>
    @endif

    {{-- Scripts --}}
    @include('layouts.custom-template.js-layouts.mainjs')
</body>

</html>
