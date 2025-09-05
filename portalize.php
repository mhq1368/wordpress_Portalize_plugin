<?php
/*
Plugin Name: افزونه پورتالایز
Plugin URI: https://qassabi.ir
Description: سفارشی‌سازی صفحه ورود Admin وردپرس با طراحی اختصاصی
Version: 1.1.0
Author: محمدحسن قصابی
Author URI: https://qassabi.ir
License: GPL2
*/
defined('ABSPATH') or exit;
define('PORTALIZE_VERSION',        '1.1.0');
define('PORTALIZE_ASSETS_URL',       plugin_dir_url(__FILE__) .    'assets/');
define('PORTALIZE_TEMPELATES_PATH',  plugin_dir_path(__FILE__) .   'templates/');
define( 'PORTALIZE_INC_PATH',        plugin_dir_path( __FILE__ ) . 'inc/' );

/** Load File authorization.php */
include_once PORTALIZE_INC_PATH . 'authourization.php';
/** Load File change_font.php */
include_once PORTALIZE_INC_PATH . 'change_font.php';
/** Load File section_security.php */
include_once PORTALIZE_INC_PATH . 'section_settings_security.php';
/** Load File section_settings.php */
include_once PORTALIZE_INC_PATH . 'section_settings.php';
/** Load File query_logo_selective.php */
//  include_once PORTALIZE_INC_PATH . 'query_logo_selective.php';
/** Load File query_background_selective.php */
//  include_once PORTALIZE_INC_PATH . 'query_background_selective.php';

include_once PORTALIZE_INC_PATH . 'show_selective_pic.php';
