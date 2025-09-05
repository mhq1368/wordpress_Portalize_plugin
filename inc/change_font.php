<?php
if ( ! defined('ABSPATH') ) exit;
// Disable Gutenberg Editor
add_action('use_block_editor_for_post', '__return_false', 100);


// Change Default Font in WordPress Admin
add_action('admin_enqueue_scripts', 'mhq_chane_font_admin_menu');
function mhq_chane_font_admin_menu()
{
    wp_enqueue_style('mhq_chane_font_admin_menu', PORTALIZE_ASSETS_URL . 'css/admin_style.css', [], '1.0', 'all');
}


// Change Default Font for Frontend
add_action('wp_enqueue_scripts', function () {
    wp_register_style('mhq_front_style_users', PORTALIZE_ASSETS_URL . 'css/style.css', [], '1.0', 'all'); 
    wp_enqueue_style('mhq_front_style_users');


});