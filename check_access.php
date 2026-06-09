<?php
session_start();

// نقش‌ها و دسترسی‌های آنها
$permissions = [
    'admin' => [
        'view_dashboard' => true,
        'manage_alerts' => true,
        'manage_disasters' => true,
        'manage_provinces' => true,
        'manage_users' => true,
        'view_reports' => true,
        'manage_settings' => true,
        'delete_anything' => true
    ],
    'moderator' => [
        'view_dashboard' => true,
        'manage_alerts' => true,
        'manage_disasters' => false,
        'manage_provinces' => false,
        'manage_users' => false,
        'view_reports' => true,
        'manage_settings' => false,
        'delete_anything' => false
    ],
    'user' => [
        'view_dashboard' => false,
        'manage_alerts' => false,
        'manage_disasters' => false,
        'manage_provinces' => false,
        'manage_users' => false,
        'view_reports' => false,
        'manage_settings' => false,
        'delete_anything' => false
    ]
];

// تابع چک کردن دسترسی
function hasPermission($permission)
{
    global $permissions;

    if (!isset($_SESSION['user_role'])) {
        return false;
    }

    $role = $_SESSION['user_role'];

    if (!isset($permissions[$role])) {
        return false;
    }

    return isset($permissions[$role][$permission]) ? $permissions[$role][$permission] : false;
}

// تابع چک کردن لاگین بودن کاربر
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// تابع گرفتن نقش کاربر
function getUserRole()
{
    return $_SESSION['user_role'] ?? 'user';
}

// تابع نمایش منو بر اساس نقش
function showMenuBasedOnRole()
{
    $role = getUserRole();
    $menu = '';

    if (hasPermission('view_dashboard')) {
        $menu .= '<a href="admin/dashboard.php">🏠 داشبورد</a>';
    }

    if (hasPermission('manage_alerts')) {
        $menu .= '<a href="admin/alerts_list.php">📢 مدیریت هشدارها</a>';
    }

    if (hasPermission('manage_users')) {
        $menu .= '<a href="admin/users_list.php">👥 مدیریت کاربران</a>';
    }

    if (hasPermission('manage_disasters')) {
        $menu .= '<a href="admin/disasters_list.php">🌋 مدیریت بلایا</a>';
    }

    if (hasPermission('view_reports')) {
        $menu .= '<a href="admin/reports.php">📊 گزارشات</a>';
    }

    if (empty($menu)) {
        $menu = '<span>شما دسترسی به هیچ بخشی ندارید</span>';
    }

    return $menu;
}
?>