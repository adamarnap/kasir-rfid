<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Example Breadcrumbs
 */

// Home > Blog
Breadcrumbs::for('blog', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Dashboard
 */

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});


/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Profile
 */
Breadcrumbs::for('profile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Profil Saya', route('profile.edit'));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Settings
 */

Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Pengaturan', "javascript:void(0)");
});

Breadcrumbs::for('navigations', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Menu', route('navs.index'));
});

Breadcrumbs::for('navigation-edit', function (BreadcrumbTrail $trail, $nav, $title = null) {
    $trail->parent('navigation');
    $trail->push("Edit Menu $title", route('navs.edit', $nav));
});

Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Pengguna', route('users.index'));
});

Breadcrumbs::for('users-create', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push('Tambah Pengguna', route('users.create'));
});

Breadcrumbs::for('users-edit', function (BreadcrumbTrail $trail, $user, $name = null) {
    $trail->parent('users');
    $trail->push("Edit Pengguna $name", route('users.edit', $user));
});

Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Peran', route('roles.index'));
});

Breadcrumbs::for('roles-permissions', function (BreadcrumbTrail $trail, $roleId, $name) {
    $trail->parent('roles');
    $trail->push("Hak Akses Peran $name", route('roles.show', $roleId));
});

Breadcrumbs::for('preferences', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push('Preferensi', route('preferences.index'));
});