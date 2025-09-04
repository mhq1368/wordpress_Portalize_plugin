<?php

// بخش ورود به صفحه پیشخوان ادمین
add_action('login_init', function () {


    // اجازه دادن به وردپرس برای اینکه عملیات لاگ اوت رو خودش انجام بده
    if (isset($_REQUEST['action']) && $_REQUEST['action'] === 'logout') {
        return;
    }
    // اگر یوزر لاگین کرده و نقش ادمین نداره → کاری نکن
    if (is_user_logged_in() && !current_user_can('manage_options')) {
        return; // بقیه همون لاگین پیش‌فرض رو ببینن
    }

    // ست کردن کوکی تست مثل هسته وردپرس
    $secure = is_ssl();
    @setcookie(TEST_COOKIE, 'WP Cookie check', time() + HOUR_IN_SECONDS, COOKIEPATH,     COOKIE_DOMAIN, $secure, true);
    @setcookie(TEST_COOKIE, 'WP Cookie check', time() + HOUR_IN_SECONDS, SITECOOKIEPATH, COOKIE_DOMAIN, $secure, true);
    // 👇 اگر POSTِ لاگین هست، نرو برای include + exit
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log'], $_POST['pwd'])) {
        return; // به هسته ی وردپرس اجازه بده خودش احراز هویت رو انجام بده
    }
    include_once PORTALIZE_TEMPELATES_PATH . 'temp.php' ?: exit;
    // echo var_dump(PORTALIZE_TEMPELATES_PATH);
    exit;
});



add_action(
    'admin_menu',
    function () {
        add_menu_page(
            'تنظیمات صفحه ورود به لاگین ادمین وردپرس',
            'ویرایش صفحه ورود',
            'manage_options',
            'mhq_login_setup',
            'mhq_page_menu_html_view',
            'dashicons dashicons-table-col-after',
            5
        );
    }
);
