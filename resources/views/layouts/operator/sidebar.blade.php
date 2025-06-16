<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @foreach($sidebar as $key => $nav)
        @can($nav->url.'.read')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs($nav->url . '.*') ? 'active': 'collapsed' }}" 
                href="{{ count($nav->child)>0 ? 'javascript:void(0);': route($nav->url.'.index') }}"
                @if(count($nav->child)>0)
                data-bs-target="#components-nav{{ $key }}" 
                data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs($nav->url . '.*') ? 'true': 'false' }}"
                @endif
                >
                <i class="{{ $nav->icon ?? 'bi bi-grid' }}"></i>
                <span>{{ $nav->name ?? 'Nav Name' }}</span>
            </a>
            @if(count($nav->child)>0)
            <ul id="components-nav{{ $key }}" class="nav-content collapse {{ request()->routeIs($nav->url . '.*') ? 'show': '' }}" data-bs-parent="#sidebar-nav">
                @foreach($nav->child as $child)
                @can($child->url.'.read')
                <li>
                    <a href="{{ route($child->url.'.index') }}" class="{{ request()->routeIs($child->url . '.*') ? 'active': '' }}">
                        <i class="bi bi-circle"></i>
                        <span>{{ $child->name ?? 'Child Name' }}</span>
                    </a>
                </li>
                @endcan
                @endforeach
            </ul>
            @endif
        </li>
        @endcan
        @endforeach
    </ul>
</aside>