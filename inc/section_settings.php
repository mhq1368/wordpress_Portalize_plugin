<?php

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