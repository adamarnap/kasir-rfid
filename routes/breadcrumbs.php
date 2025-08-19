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
 * Transactions
 */

Breadcrumbs::for('transactions', function (BreadcrumbTrail $trail) {
    $trail->push('Transaksi', route('transactions.index'));
});

Breadcrumbs::for('active-transactions', function (BreadcrumbTrail $trail) {
    $trail->parent('transactions');
    $trail->push('Transaksi Aktif', route('active-transactions.index'));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Topup
 */

Breadcrumbs::for('topup', function (BreadcrumbTrail $trail) {
    $trail->push('Top Up', route('topup.index'));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * RFID
 */

Breadcrumbs::for('rfid', function (BreadcrumbTrail $trail) {
    $trail->push('RFID', route('rfid'));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Balance
 */
Breadcrumbs::for('balance', function (BreadcrumbTrail $trail) {
    $trail->push('Cek Saldo', route('balance.index'));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Master
 */

Breadcrumbs::for('master', function (BreadcrumbTrail $trail) {
    $trail->push('Master', 'javascript:void(0);');
});

// Master > Categories
Breadcrumbs::for('categories', function (BreadcrumbTrail $trail) {
    $trail->parent('master');
    $trail->push('Kategori', route('master.categories.index'));
});

// Master > Products
Breadcrumbs::for('products', function (BreadcrumbTrail $trail) {
    $trail->parent('master');
    $trail->push('Produk', route('master.products.index'));
});

// Master > Students
Breadcrumbs::for('students', function (BreadcrumbTrail $trail) {
    $trail->parent('master');
    $trail->push('Siswa', route('master.students.index'));
});

// Master > Parents
Breadcrumbs::for('parents', function (BreadcrumbTrail $trail) {
    $trail->parent('master');
    $trail->push('Wali Siswa', route('master.parents.index'));
});

/**
 * ------------------------------------------------------------------------------------------------------------------------
 * Report
 */

Breadcrumbs::for('reports', function (BreadcrumbTrail $trail) {
    $trail->push('Laporan', 'javascript:void(0);');
});

// Report > Transactions
Breadcrumbs::for('report.transactions', function (BreadcrumbTrail $trail) {
    $trail->parent('reports');
    $trail->push('Riwayat Transaksi', route('report.transactions.index'));
});

// Report > Transactions > Show
Breadcrumbs::for('report.transactions.show', function (BreadcrumbTrail $trail, $student) {
    $trail->parent('report.transactions');
    $trail->push("Detail Transaksi Siswa - {$student->userData->name}", route('report.transactions.show', $student->student_id));
});

// Report > TopUp
Breadcrumbs::for('report.topup', function (BreadcrumbTrail $trail) {
    $trail->parent('reports');
    $trail->push('Riwayat Top Up', route('report.topup.index'));
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