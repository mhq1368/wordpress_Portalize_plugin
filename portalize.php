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
define('PORTALIZE_ASSETS_URL',  plugin_dir_url(__FILE__) . 'assets/');
define('PORTALIZE_TEMPELATES_PATH',  plugin_dir_path(__FILE__) . 'templates/');
// تعریف مسیر پوشه inc
define( 'PORTALIZE_INC_PATH', plugin_dir_path( __FILE__ ) . 'inc/' );

// لود فایل authorization.php
include_once PORTALIZE_INC_PATH . 'authourization.php';
// لود فایل change_font.php
include_once PORTALIZE_INC_PATH . 'change_font.php';
//لود فایل section_security.php
include_once PORTALIZE_INC_PATH . 'section_settings_security.php';
//لود فایل section_settings.php
include_once PORTALIZE_INC_PATH . 'section_settings.php';
//لود فایل jquery.php
include_once PORTALIZE_INC_PATH . 'jquery.php';











// echo '<pre>';
// echo var_dump(PORTALIZE_INC_PATH);
// echo '</pre>';