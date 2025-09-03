<?php
/*
Plugin Name: افزونه پورتالایز
Plugin URI: https://qassabi.ir
Description: سفارشی‌سازی صفحه ورود Admin وردپرس با طراحی اختصاصی
Version: 1.0
Author: محمد
Author URI: https://qassabi.ir
License: GPL2
*/



defined('ABSPATH') or exit;
define('Assets',  plugin_dir_url(__FILE__) . 'assets/');
define('temp',  plugin_dir_path(__FILE__) . 'template/');





// غیرفعالسازی ویرایشگر کوتنبرگ برای نوشته ها
add_action('use_block_editor_for_post', '__return_false', 100);


// تغییر فونت پیش فرض پیشخوان ادمین وردپرس
add_action('admin_enqueue_scripts', 'mhq_chane_font_admin_menu');
function mhq_chane_font_admin_menu()
{
    wp_enqueue_style('mhq_chane_font_admin_menu', Assets . 'css/admin_style.css', [], '1.0', 'all');
}


// تغییر فونت پیش فرض کل سایت
add_action('wp_enqueue_scripts', function () {
    wp_register_style('mhq_front_style_users', Assets . 'css/style.css', [], '1.0', 'all'); // بدون فایل واقعی
    wp_enqueue_style('mhq_front_style_users');
    // wp_add_inline_style('mhq-front-font', $mycss);


});




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
    include_once temp . 'temp.php' ?: exit;
    echo var_dump(temp);
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

// ایجاد بخش مدیریت افزونه جهت ادمین
add_action('admin_init', function () {
    add_settings_section('portaliz_login_section', 'تنظیمات صفحه ورود', '__return_false', 'portaliz-login');
    //تیتر پیام های مناسبتی
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



    //متن پیام های مناسبتی
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

    // لوگوی صفحه ی ورود
    register_setting('mhq_login_fields_group', 'portalize_img_logo', [
        'type'              => 'integer',
        'sanitize_callback' => 'absint',
    ]);
    add_settings_field('portalize_img_logo', 'لوگو', function () {
        $logo_img_id = (int) get_option('portalize_img_logo', 0);
        $logo_img_url = $logo_img_id ? wp_get_attachment_image_url($logo_img_id, 'medium') : '';
?>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            <div id="portaliz-logo-preview" style="width:120px;height:120px;background:#f1f1f1;border:1px solid #ddd;border-radius:8px;display:grid;place-items:center;overflow:hidden">
                <?php if ($logo_img_url): ?>
                    <img src="<?php echo esc_url($logo_img_url); ?>" alt=""
                        style="max-width:100%;max-height:100%;display:block">
                <?php else: ?>
                    <span style="color:#666">بدون لوگو</span>
                <?php endif; ?>
            </div>

            <div style="display:flex;gap:8px">
                <input type="hidden" id="portaliz_logo_img" name="portalize_img_logo" value="<?php echo esc_attr($logo_img_id); ?>">
                <button type="button" class="button" id="portaliz-logo-choose">انتخاب لوگو</button>
                <button type="button" class="button button-secondary" id="portaliz-logo-remove" <?php echo $logo_img_id ? '' : ' disabled'; ?>>حذف</button>
            </div>
        </div>
    <?php
    }, 'portaliz-login', 'portaliz_login_section');

    // فیلد پس زمینه صفحه ی ورود
    register_setting("mhq_login_fields_group", 'portalize_img_bg', [
        'type'              => 'integer',
        'sanitize_callback' => 'absint',
    ]);
    add_settings_field('portalize_img_bg', 'تصویر پس زمینه', function () {
        $bg_id = (int) get_option('portalize_img_bg', 0);
        $bg_url = $bg_id ? wp_get_attachment_image_url($bg_id, 'medium') : '';
    ?>
        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            <div id="portaliz-bg-preview" style="width:160px;height:90px;background:#f1f1f1;border:1px solid #ddd;border-radius:8px;display:grid;place-items:center;overflow:hidden">
                <?php if ($bg_url): ?>
                    <img src="<?php echo esc_url($bg_url); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
                <?php else: ?>
                    <span style="color:#666">بدون تصویر</span>
                <?php endif; ?>
            </div>

            <div style="display:flex;gap:8px">
                <input type="hidden" id="portaliz_bg_img" name="portalize_img_bg" value="<?php echo esc_attr($bg_id); ?>">
                <button type="button" class="button" id="portaliz-bg-choose">انتخاب تصویر</button>
                <button type="button" class="button button-secondary" id="portaliz-bg-remove" <?php echo $bg_id ? '' : ' disabled'; ?>>حذف</button>
            </div>
        </div>
    <?php
    }, 'portaliz-login', 'portaliz_login_section');











    // نمایش صفحه ی تنظیمات با ایتم های بالا
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

// jquery ها جهت باکس های انتخاب لوگو 
add_action('admin_enqueue_scripts', function ($hook) {


    wp_enqueue_script('jquery');
    wp_enqueue_media();

    wp_register_script('portaliz-logo-admin', false, ['jquery'], '1.0', true);
    $js = <<<JS
    jQuery(function($){
      var frame;
      $('#portaliz-logo-choose').on('click', function(e){
        e.preventDefault();
        if (frame) { frame.open(); return; }
        frame = wp.media({
          title: 'انتخاب لوگو',
          button: { text: 'استفاده از این تصویر' },
          library: { type: 'image' },
          multiple: false
        });
        frame.on('select', function(){
          var attachment = frame.state().get('selection').first().toJSON();
          var url = attachment.url;
          if (attachment.sizes && attachment.sizes.medium) {
            url = attachment.sizes.medium.url;
          }
          $('#portaliz_logo_img').val(attachment.id);
          $('#portaliz-logo-preview').html('<img src="'+ url +'" style="max-width:100%;max-height:100%;display:block">');
          $('#portaliz-logo-remove').prop('disabled', false);
        });
        frame.open();
      });

      $('#portaliz-logo-remove').on('click', function(e){
        e.preventDefault();
        $('#portaliz_logo_img').val('');
        $('#portaliz-logo-preview').html('<span style="color:#666">بدون لوگو</span>');
        $(this).prop('disabled', true);
      });
    });
    JS;
    wp_add_inline_script('portaliz-logo-admin', $js);
    wp_enqueue_script('portaliz-logo-admin');
});

// jquery انتخاب تصویر پس زمینه
add_action('admin_enqueue_scripts', function ($hook) {
    // $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    // $hook_id = 'settings_page_portaliz-login';
    // if ($hook !== $hook_id && (!$screen || $screen->id !== $hook_id)) return;

    wp_enqueue_script('jquery');
    wp_enqueue_media();

    wp_register_script('portaliz-media-admin', false, ['jquery'], '1.1', true);
    $js = <<<JS
    jQuery(function($){
      function bindMedia(chooseBtn, removeBtn, hiddenInput, previewBox){
        var frame;
        $(chooseBtn).on('click', function(e){
          e.preventDefault();
          if (frame) { frame.open(); return; }
          frame = wp.media({
            title: 'انتخاب تصویر',
            button: { text: 'استفاده از این تصویر' },
            library: { type: 'image' },
            multiple: false
          });
          frame.on('select', function(){
            var a = frame.state().get('selection').first().toJSON();
            var url = (a.sizes && a.sizes.medium) ? a.sizes.medium.url : a.url;
            $(hiddenInput).val(a.id);
            $(previewBox).html('<img src="'+ url +'" style="width:100%;height:100%;object-fit:cover;display:block">');
            $(removeBtn).prop('disabled', false);
          });
          frame.open();
        });

        $(removeBtn).on('click', function(e){
          e.preventDefault();
          $(hiddenInput).val('');
          $(previewBox).html('<span style="color:#666">بدون تصویر</span>');
          $(this).prop('disabled', true);
        });
      }

      // لوگو
      bindMedia('#portaliz-logo-choose', '#portaliz-logo-remove', '#portaliz_logo_img', '#portaliz-logo-preview');
      // بکگراند
      bindMedia('#portaliz-bg-choose',   '#portaliz-bg-remove',   '#portaliz_bg_img',   '#portaliz-bg-preview');
    });
    JS;
    wp_add_inline_script('portaliz-media-admin', $js);
    wp_enqueue_script('portaliz-media-admin');
});






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


// echo '<pre>';
// echo var_dump(get_current_screen());
// echo '</pre>';