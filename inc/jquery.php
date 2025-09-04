<?php


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

