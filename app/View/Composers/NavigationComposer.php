<?php

// app/Http/ViewComposers/NavigationComposer.php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NavigationComposer
{
    public function compose(View $view)
    {
        $navs = Navigation::with(['child' => function ($query) {
            $query->where('active', 1)
                ->where('display', true)
                ->orderBy('order', 'asc');
        }])
            ->whereNull('parent_id')
            ->where('active', true)
            ->where('display', true)
            ->orderBy('order')
            ->get()
            ->map(function ($nav) {
                $nav->url = $nav->url != '#' ? route($nav->url) : '#';  // $nav->url yang akan dihasilkan adalah dalam bentuk route (contoh : route('dashboard'), hasilnya http://127.0.0.1:8000/dashboard)

                $nav->child->each(function ($child) {
                    $child->url = $child->url != '#' ? route($child->url) : '#';
                });

                return $nav;
            });
        $dashNavs = $navs->where('slug', 'dashboard')->first()->toArray();
        $filteredNavs = $this->filterPermission($navs->toArray());
        if (!collect($filteredNavs)->contains($dashNavs)) {
            // put dashboard at the beginning of the array
            array_unshift($filteredNavs, $dashNavs);
        }

        $view->with('navs',  $filteredNavs);
    }

    private function filterPermission($navs)
    {
        if (empty($navs)) {
            return [];
        }
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $filteredNavs = [];
        foreach ($navs as $key => $nav) {
            // Check if user has permission for this navigation item
            if ($user->can($nav['slug'] . '.read')) {
                // If there are child items, recursively filter them
                if (!empty($nav['child'])) {
                    $nav['child'] = $this->filterPermission($nav['child']);
                }
                $filteredNavs[] = $nav; // Add to the result array
            }
        }

        return $filteredNavs; // Return the filtered array
    }
}
