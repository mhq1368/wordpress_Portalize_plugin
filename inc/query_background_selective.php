<?php
if (! defined('ABSPATH')) exit;
// //  Jquery for Selective Background In AdminPage
add_action('admin_enqueue_scripts', function () {

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

      
      // Background
      bindMedia('#portaliz-bg-choose',   '#portaliz-bg-remove',   '#portaliz_bg_img',   '#portaliz-bg-preview');
    });
    JS;
  wp_add_inline_script('portaliz-media-admin', $js);
  wp_enqueue_script('portaliz-media-admin');
});


