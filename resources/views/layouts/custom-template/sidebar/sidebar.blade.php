<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        @php
            $dashboard = array_filter($navs, function ($nav) {
                return $nav['name'] == 'Dashboard';
            });
            $dashboard = array_shift($dashboard);
        @endphp
        <a href="{{ $dashboard['url'] }}" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ Storage::url($prefs_composer['logo']) }}" alt="APP Logo" class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">{{ $prefs_composer['app_name'] }}</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Menu List -->
    @include('layouts.custom-template.sidebar.partial.menu-list')
    <!--end::Menu List -->
</aside>
<!--end::Sidebar-->
