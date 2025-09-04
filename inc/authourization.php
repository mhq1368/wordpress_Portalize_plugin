<?php

// خالی بودن فیلدها قبل از احراز هویت
add_filter('authenticate', function ($user, $username, $password) {
    if (isset($_POST['log'])) {
        if ($username === '') {
            wp_safe_redirect(add_query_arg('plz_toast', 'empty_username', wp_login_url()));
            exit;
        }
        if ($password === '') {
            wp_safe_redirect(add_query_arg('plz_toast', 'empty_password', wp_login_url()));
            exit;
        }
    }
    return $user;
}, 20, 3);

// نامعتبر بودن ورود (یوزرنیم غلط یا پسورد غلط)
add_action('wp_login_failed', function ($username) {
    $code = 'invalid_username';
    if ($username !== '') {
        $user = get_user_by('login', $username);
        if ($user) $code = 'incorrect_password';
    }
    wp_safe_redirect(add_query_arg('plz_toast', $code, wp_login_url()));
    exit;
});

