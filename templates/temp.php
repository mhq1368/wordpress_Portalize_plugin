<?php if (!defined('ABSPATH')) exit; ?>
<?php
/**
 * The template for displaying the login and lostpassword forms
 */
$action = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : 'login';
if (isset($_GET['loggedout']) && $_GET['loggedout'] === 'true') {
    $action = 'login';
}
if (isset($_GET['reauth']) && $_GET['reauth'] === '1') {
    $action = 'login';
}
?>

<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
        <?php bloginfo('name'); ?>
    </title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" />

    <link rel="stylesheet" href="<?= esc_url(PORTALIZE_ASSETS_URL . 'css/style.css'); ?>">

    <?php
    ?>
    <?php wp_head(); ?>
</head>

<body id='portaliz-login'>

    <main class="layout">
        <?php
        $hero_title = get_option('portalize_title', 'فرا رسیدن ماه ربیع الاول مبارک باد');
        $hero_body  = get_option('portalize_body',  'بوی خوش <strong>ربیع الاول</strong> به مشام می‌رسد…');
        ?>

        <?php
        $bg_id  = (int) get_option('portalize_img_bg', 0);
        $bg_url = $bg_id ? wp_get_attachment_image_url($bg_id, 'full') :
            PORTALIZE_ASSETS_URL . 'img/bg.png';
        ?>


        <!-- ستون تصویر و متن‌های مناسبتی -->
        <section class="hero" role="img" aria-label="پس‌زمینه"
            style="
            background-image: url('<?= $bg_url; ?>');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            ">
            <div class="hero-content">
                <div class="hero-text">
                    <div class="hero-top"><?= $hero_title; // قبلاً با wp_kses پاکسازی شده 
                                            ?></div>
                    <?= $hero_body; // قبلاً با wp_kses پاکسازی شده 
                    ?>
                </div>

            </div>
            <div class="hero-content">
                <div class="hero-text">
                    <footer class="footer">
                        <p>

                        <p>
                            این افزونه توسط محمدحسن قصابی نوشته شده و تمامی حقوق آن اعم از تغییرات محتوایی و تغییرات کدنویسی متعلق به ایشان می باشد و هرگونه کپی برداری پیگرد قانونی دارد.
                        </p>

                        <p>
                    </footer>
                </div>

            </div>

        </section>
        <?php
        $logo_id  = (int) get_option('portalize_img_logo');
        $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full')
            : PORTALIZE_ASSETS_URL . 'img/logo.png';

        ?>
        <!-- ستون پنل ورود -->
        <aside class="login" aria-label="پنل ورود">
            <div class="card" role="form" aria-labelledby="form-title">

                <div class="brand">
                    <div class="logo" aria-hidden="true">
                        <img src="<?= $logo_url ?>" alt="لوگو" width="185" height="185">
                    </div>
                    <h1 id="form-title">
                        <small>
                            افزونه پورتالایز - <?= ($action === 'lostpassword') ? 'فرم فرآموشی گذرواژه' : 'فرم ورود'; ?>
                        </small>
                    </h1>
                </div>





                <!-- WordPress Login Form -->
                <form action="<?= esc_url(wp_login_url()); ?>" method="post" novalidate
                    <?php if ($action !== 'login') echo 'hidden'; ?>>
                    <div class="field">
                        <input id="user_login" name="log" type="text" autocomplete="username" placeholder="نام کاربری" required>
                    </div>

                    <div class="field">
                        <input id="user_pass" name="pwd" type="password" autocomplete="current-password" placeholder="گذرواژه" required>
                    </div>

                    <div class="tiny">
                        <label style="display:flex; align-items:center; gap:6px; user-select:none;">
                            <input type="checkbox" name="rememberme" value="forever" style="accent-color:var(--primary)">
                            مرا به خاطر بسپار
                        </label>
                        <a href="<?= esc_url(wp_lostpassword_url()); ?>">فراموشی گذرواژه؟</a>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn">ورود</button>
                    </div>

                    <p class="note">ملاحظهٔ امنیتی: پس از اتمام کار، با استفاده از گزینه «خروج»، از سامانه خارج شوید.</p>

                    <p class="gotosite">
                        <a href="<?= get_site_url() ?>"> 🌐 رفتن به <?php bloginfo('name'); ?></a>
                    </p>

                    <input type="hidden" name="redirect_to" value="<?= esc_url(admin_url()); ?>">
                </form>



                <form action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword', 'login_post')); ?>"
                    <?php if ($action !== 'lostpassword') echo 'hidden'; ?>
                    method="post">
                    <div class="field">
                        <input id="user_login" type="text" name="user_login" placeholder="نام کاربری یا ایمیل" required>
                    </div>
                    <div class="actions">
                        <button type="submit" class="btn">دریافت لینک بازیابی</button>
                    </div>
                    <p class="note">

                    <p class="gotosite">
                        <a href="<?php echo esc_url(wp_login_url()); ?>">بازگشت به فرم ورود</a>
                    </p>

                    <p>
                </form>
            </div>
        </aside>


    </main>
    <footer>
        <?php wp_footer();?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

        <script>
            jQuery(function($) {
                /**
                 * 1) Our custom codes that are set in the URL through the plugin (for example, plz_toast=empty_username)
                 */
                var toastCode = <?php echo isset($_GET['plz_toast']) ?
                                    json_encode(sanitize_text_field($_GET['plz_toast'])) : 'null'; ?>;

                // 2) Messages Map To Persian
                var msgs = {
                    empty_username: 'خطا: نام کاربری خالی است.',
                    empty_password: 'خطا: کادر رمز خالی است.',
                    incorrect_password: 'خطا: رمز عبوری که برای این نام کاربری وارد شده درست نیست.',
                    invalid_username: 'خطا: چنین نام کاربری‌ای وجود ندارد.',
                    logged_out: 'با موفقیت خارج شدید.',
                    reset_email_sent: 'لینک بازیابی گذرواژه به ایمیل شما ارسال شد.',
                    session_expired: 'نشست شما منقضی شده، لطفاً دوباره وارد شوید.',
                    invalid_email_combo: 'ایمیل/نام کاربری معتبر نیست.'
                };

                function showToast(text, type) {
                    $.toast({
                        heading: 'پیام سیستم',
                        text: text,
                        showHideTransition: 'fade',
                        icon: type || 'error', // error | info | success | warning
                        position: 'top-center',
                        loaderBg: '#1565c0',
                        hideAfter: 3000,
                        stack: 3,
                        textAlign: 'right',

                    });
                }

                /**
                 *  Show toast based on custom URL param (plz_toast) set from our hooks in authourization.php
                 */

                if (toastCode && msgs[toastCode]) {
                    var type = (toastCode === 'logged_out' || toastCode === 'reset_email_sent') ? 'info' : 'error';
                    showToast(msgs[toastCode], type);
                }

                /**
                 * 4) show message based on other WP login URL params(logout/checkemail/reauth/errors)
                 */
                <?php if (isset($_GET['checkemail']) && $_GET['checkemail'] === 'confirm'): ?>
                    showToast(msgs.reset_email_sent, 'info');
                <?php endif; ?>

                <?php if (isset($_GET['loggedout']) && $_GET['loggedout'] === 'true'): ?>
                    showToast(msgs.logged_out, 'info');
                <?php endif; ?>

                <?php if (isset($_GET['reauth']) && $_GET['reauth'] === '1'): ?>
                    showToast(msgs.session_expired, 'warning');
                <?php endif; ?>

                <?php if (isset($_GET['errors'])): ?>
                        (function() {
                            var e = <?php echo json_encode(sanitize_text_field($_GET['errors'])); ?>;
                            if (e === 'invalid_email' || e === 'invalidcombo') {
                                showToast(msgs.invalid_email_combo, 'error');
                            }
                        })();
                <?php endif; ?>

                    /**
                     * 5) After displaying, clear the queries so they don’t appear again on refresh
                     */
                    (function cleanQuery() {
                        if (!history.replaceState) return;
                        var url = new URL(window.location.href);
                        ['plz_toast', 'checkemail', 'loggedout', 'reauth', 'errors'].forEach(function(k) {
                            url.searchParams.delete(k);
                        });
                        history.replaceState({}, '', url.toString());
                    })();
            });
        </script>

    </footer>

</body>

</html>