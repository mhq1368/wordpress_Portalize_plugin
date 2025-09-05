<?php
if (! defined('ABSPATH')) exit;
//  Jquery for Selective Logo In AdminPage
add_action('admin_enqueue_scripts', function () {

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
      // Logo
      bindMedia('#portaliz-logo-choose', '#portaliz-logo-remove', '#portaliz_logo_img', '#portaliz-logo-preview');
    });
    JS;
    wp_add_inline_script('portaliz-logo-admin', $js);
    wp_enqueue_script('portaliz-logo-admin');
});
