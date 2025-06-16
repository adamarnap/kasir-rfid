<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\NavigationComposer;
use App\View\Composers\PreferenceComposer;
use App\View\Composers\FormComposer;
use App\View\Composers\ThemeModeComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['layouts.custom-template.sidebar.sidebar'], NavigationComposer::class);
        View::composer(['layouts.custom-template.topbar.topbar'], NavigationComposer::class);
        View::composer(['layouts.custom-template.main'], PreferenceComposer::class);
        View::composer(['layouts.custom-template.main'], ThemeModeComposer::class);
        // View::composer(['landingpage.welcome'], PreferenceComposer::class);

        // Auth
        View::composer(['auth.login'], PreferenceComposer::class);
        View::composer(['auth.register'], PreferenceComposer::class);
        View::composer(['auth.verify-email'], PreferenceComposer::class);
        View::composer(['auth.forgot-password'], PreferenceComposer::class);
        View::composer(['auth.reset-password'], PreferenceComposer::class);
        View::composer(['components.mail-layout'], PreferenceComposer::class);
    }
}
