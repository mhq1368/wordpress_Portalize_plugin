<?php
if (! defined('ABSPATH')) exit;
/**
 * Create Section Management Plugin
 */
add_action('admin_init', function () {
    add_settings_section('portaliz_login_section', 'تنظیمات صفحه ورود', '__return_false', 'portaliz-login');


    // Greeting Message Title
    register_setting('mhq_login_fields_group', 'portalize_title', [
        'type' => 'string',
        'sanitize_callback' => function ($v) {
            return wp_kses($v, ['strong' => [], 'em' => [], 'b' => [], 'i' => [], 'br' => []]);
        },
        'default' => 'فرا رسیدن ماه ربیع الاول مبارک باد',
    ]);
    add_settings_field('portalize_title', 'عنوان پیام مناسبتی', function () {
        $val = get_option('portalize_title', '');
        echo '<input type="text" name="portalize_title" value="' . esc_attr($val) .
            '" class="regular-text" style="width: 100%">';
        echo '<p class="description">اجازه‌ی تگ‌های ساده: <code>&lt;strong&gt;</code>، <code>&lt;em&gt;</code>، <code>&lt;br&gt;</code></p>';
    }, 'portaliz-login', 'portaliz_login_section');

    /**
     *  Greeting Message 
     */
    register_setting('mhq_login_fields_group', 'portalize_body', [
        'type' => 'string',
        'sanitize_callback' => function ($v) {
            return wp_kses($v, ['strong' => [], 'em' => [], 'b' => [], 'i' => [], 'br' => []]);
        },
        'default' => 'بوی خوش <strong>ربیع الاول</strong> به مشام می‌رسد…',
    ]);
    add_settings_field('portalize_body', 'متن پیام مناسبتی', function () {
        $val = get_option('portalize_body', '');
        echo '<textarea name="portalize_body" rows="5" class="large-text" style="width: 100%">' . esc_textarea($val) . '</textarea>';
        echo '<p class="description">می‌تونی از <code>&lt;strong&gt;</code> و <code>&lt;br&gt;</code> استفاده کنی.</p>';
    }, 'portaliz-login', 'portaliz_login_section');

    // LoginPage Logo Fields
    register_setting('mhq_login_fields_group', 'portalize_img_logo', [
        'type'              => 'integer',
        'sanitize_callback' => 'absint',
    ]);
    add_settings_field('portalize_img_logo', 'انتخاب لوگو', function () {
        PORTALIZE_MEDIA_FIELDS::render([
            'option_name' => 'portalize_img_logo',
            'label'       => 'انتخاب لوگو',
            'width'       => 120,
            'height'      => 120,
            'modalTitle'       => 'انتخاب لوگو',
            'hint'       => 'بدون لوگو',
        ]);
    }, 'portaliz-login', 'portaliz_login_section');


    /** 
     *  BG LoginPage Fields
     */
    register_setting("mhq_login_fields_group", 'portalize_img_bg', [
        'type'              => 'integer',
        'sanitize_callback' => 'absint',
    ]);
    add_settings_field('portalize_img_bg', 'انتخاب تصویر', function () {
        PORTALIZE_MEDIA_FIELDS::render([
            'option_name' => 'portalize_img_bg',
            'label'       => 'انتخاب تصویر',
            'width'       => 150,
            'height'      => 100,
            'modalTitle'       => 'انتخاب پس زمینه',
            'hint'       => 'بدون پس زمینه',
        ]);
    }, 'portaliz-login', 'portaliz_login_section');

    /**
     * Show Settings Page Fields
     */
    function mhq_page_menu_html_view()
    {
        if (!current_user_can('manage_options')) return;
?>
        <div class="wrap">
            <h1>Potaliz Login — تنظیمات صفحه ورود</h1>
            <form method="post" action="options.php" style="width: 80%;">
                <?php
                settings_fields('mhq_login_fields_group');
                do_settings_sections('portaliz-login');
                submit_button('ذخیره تغییرات');
                ?>
            </form>
        </div>
<?php

    }
});
