<div class="sidebar-wrapper">
    <nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
            @foreach ($navs as $nav)
                {{-- Route prefix to determine menu activity  --}}
                @php
                    /* 
                    * ========================================================================================================
                    * DISINI url nav yang bersumber dari DB yang tadinya hanya berupa nama route (ex : dashboard.index) telah diubah menjadi URL hasil dari route($nav->url)
                    * Contoh jadinya, $nav['url'] = route('dashboard.index'), yang dimana hasilnya akan berbentuk URL (ex $nav['url'] : http://127.0.0.0:8000/admin/dashboard)
                    * ========================================================================================================
                    * ========================================================================================================
                    * $navUrl, untuk mengambil path url dari menu yang diambil dari database
                    * fungsi pase_url(), Fungsi ini digunakan untuk memecah sebuah URL menjadi komponen-komponennya, seperti skema, host, path, query, dll.
                    * PHP_URL_PATH untuk mengambil path url yang berasal dari parse_url() yanng sebelumnya berupa array (skema, host, path, query, dll)
                    * ltrim() untuk menghapus karakter spasi atau karakter lain dari sisi kiri string. Disini digunakan untuk menghapus karakter "/" dari sisi kiri string
                    * Contoh: $navUrl = 127.0.0.0:8000/admin, maka hasilnya adalah admin
                    * ========================================================================================================
                    */
                    $navUrl = ltrim(parse_url($nav['url'], PHP_URL_PATH), '/');
                    /* 
                    * ========================================================================================================
                    * $routeName, untuk mengambil nama route yang sedang aktif
                    * Contoh: routeName = admin.dashboard
                    * ========================================================================================================
                    */
                    $routeName = Route::currentRouteName();
                    /* 
                    * ========================================================================================================
                    * $routePrefix, untuk mengambil prefix dari route yang sedang aktif
                    * Contoh: routeName = admin.dashboard, maka prefixnya adalah admin
                    * ========================================================================================================
                    */
                    $routePrefix = explode('.', $routeName)[0];
                @endphp

                {{-- Determines between a single menu and a menu that has children --}}
                @if (count($nav['child']) == 0)
                    {{-- Single menu --}}
                    <li class="nav-item">
                        {{-- Proses pengecekan, apakah route prefix yang sedang aktif saat ini, sama dengan url yang tersimpan pada DB atau tidak --}}
                        {{-- Contoh : $routePrefix adalah : admin, lalu $navUrl adalah admin, maka ini true, lalu seharusnya dapat memicu aktif pada navigasi UI --}}
                        <a href="{{ $nav['url'] }}"
                            class="nav-link {{ $routePrefix == $navUrl ? 'active' : '' }}">
                            <i class="{{ $nav['icon'] }}"></i>
                            <p>{{ $nav['name'] }}</p>
                        </a>
                    </li>
                @else
                    {{-- Menu with children --}}
                    @php
                        /* 
                        * ========================================================================================================
                        * $urlCurrent, untuk mengambil url yang sedang aktif
                        *
                        * Request::segments() untuk mengambil segment url yang sedang aktif
                        * Contoh: Request::segments() = ['admin', 'dashboard'], maka hasilnya adalah admin/dashboard
                        * Disini, karena induk menu, maka yang diambil hanya segment pertama saja
                        * 
                        * url('/') untuk mengambil url dari root project
                        * Contoh: url('/') = 127.0.0.1:8000
                        *
                        * Lalu jika digabungkan, maka hasilnya adalah
                        * Contoh: urlCurrent = 127.0.0.1:8000/admin/dashboard
                        * ========================================================================================================

                        * ========================================================================================================
                        * Request::is untuk mengecek apakah request yang sedang aktif, sama dengan request yang diinginkan
                        * Contoh: kita mendapati pengecekan dari parse_url($nav['url'], PHP_URL_PATH) yakni url dari DB yang hasilnya adalah admin/dashboard
                        * Lalu kita cek [dengan menggunakan Request::is()] apakah request yang sedang aktif di browser, sama dengan admin/dashboard atau tidak
                        * ========================================================================================================

                        * ========================================================================================================
                        * collect($nav['child'])->pluck('url') untuk mengambil kumpulan url dari child menu [anak dari parent menu tersebut] dari DB yang akan disajikan dalam bentuk array
                        * Contoh: collect($nav['child'])->pluck('url') = ['admin/dashboard', 'admin/profile']
                        * contains($urlCurrent) untuk mengecek apakah url yang sedang aktif, terdapat dalam array yang dihasilkan oleh collect($nav['child'])->pluck('url')
                        * ========================================================================================================
                        */
                        $urlCurrent = url('/') . '/' . Request::segments()[0];
                    @endphp
                    <li
                        class="nav-item {{ Request::is(ltrim(parse_url($nav['url'], PHP_URL_PATH), '/')) ||collect($nav['child'])->pluck('url')->contains($urlCurrent)? 'menu-open': '' }}">
                        {{-- Parent Menu --}}
                        <a href="javascript: void(0);"
                            class="nav-link {{ Request::is(ltrim(parse_url($nav['url'], PHP_URL_PATH), '/')) ||collect($nav['child'])->pluck('url')->contains($urlCurrent)? 'active': '' }}">
                            <i class="{{ $nav['icon'] }}"></i>
                            <p>
                                {{ $nav['name'] }}
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>


                        <ul class="nav nav-treeview">
                            {{-- Childs Menu --}}
                            @foreach ($nav['child'] as $child)
                                <li class="nav-item">
                                    <a href="{{ $child['url'] }}" class="nav-link {{ $urlCurrent == $child['url'] ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-circle"></i>
                                        <p>{{ $child['name'] }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</div>
