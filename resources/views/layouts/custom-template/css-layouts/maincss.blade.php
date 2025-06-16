<!-- [Main HTML Template CSS] -->
<link rel="stylesheet" href="{{ URL::asset('storage/lte/css/adminlte.css') }}" />

{{-- [Other CSS] --}}
@if (View::hasSection('auth-content'))
    @include('layouts.custom-template.css-layouts.auth-layouts.head-css')
@else
    @include('layouts.custom-template.css-layouts.app-layouts.head-css')
@endif

{{-- [Stack CSS] --}}
@stack('css')
