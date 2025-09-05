<?php

if (!defined('ABSPATH')) exit;


class PORTALIZE_MEDIA_FIELDS
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'portalize_enqueue_scripts']);
    }
    public function portalize_enqueue_scripts()
    {
        wp_enqueue_media();
        wp_add_inline_script('jquery-core', $this->get_js());
    }
    private function get_js()
    {
        return <<<JS
        function bindMedia(chooseBtn, removeBtn, hiddenInput, previewBox,modalTitle,hint){
            var frame;
            jQuery(chooseBtn).on('click', function(e){
                e.preventDefault();
                frame = wp.media({
                    title: modalTitle|| 'انتخاب تصویر',
                    button: { text: 'استفاده' },
                    library: { type: 'image' },
                    multiple: false
                });
                frame.on('select', function(){
                    var a = frame.state().get('selection').first().toJSON();
                    var url = (a.sizes && a.sizes.medium) ? a.sizes.medium.url : a.url;
                    jQuery(hiddenInput).val(a.id);
                    jQuery(previewBox).html('<img src="'+url+'" style="width:100%;height:100%;object-fit:cover">');
                    jQuery(removeBtn).prop('disabled', false);
                });
                frame.open();
            });

            jQuery(removeBtn).on('click', function(e){
                e.preventDefault();
                jQuery(hiddenInput).val('');
                jQuery(previewBox).html('<span style="color:#666"><?=hint ?></span>');
                jQuery(this).prop('disabled', true);
            });
        }
JS;
    }
// این متد هم HTML لازم رو می‌سازه
    public static function render($args = []) {
        $defaults = [
            'option_name' => '',        // اسم option در وردپرس
            'label'       => 'انتخاب تصویر',
            'width'       => 160,
            'height'      => 90,
            'modalTitle'=>'انتخاب تصویر',
            'hint'=>'بدون تصویر',
        
        ];
        $args = wp_parse_args($args, $defaults);
        if ( empty($args['option_name']) ) return;

        $id      = sanitize_key($args['option_name']);
        $val_id  = (int) get_option($args['option_name'], 0);
        $val_url = $val_id ? wp_get_attachment_image_url($val_id, 'medium') : '';
        ?>
        <div style="margin:10px 0;display:flex;align-items:center;gap:12px;flex-wrap:wrap">
            <div id="<?php echo $id; ?>-preview"
                 style="width:<?php echo $args['width']; ?>px;height:<?php echo $args['height']; ?>px;
                        background:#f1f1f1;border:1px solid #ddd;display:grid;place-items:center">
                <?php if ($val_url): ?>
                    <img src="<?php echo esc_url($val_url); ?>" style="width:100%;height:100%;object-fit:cover">
                <?php else: ?>
                    <span style="color:#666"> <?=$args['hint'] ?? 'بدون تصویر' ?></span>
                <?php endif; ?>
            </div>
            <div style="display:flex;gap:8px">
                <input type="hidden" id="<?php echo $id; ?>_input" 
                       name="<?php echo $args['option_name']; ?>" 
                       value="<?php echo esc_attr($val_id); ?>">
                <button type="button" class="button" id="<?php echo $id; ?>-choose"><?php echo $args['label']; ?></button>
                <button type="button" class="button" id="<?php echo $id; ?>-remove" <?php echo $val_id ? '' : 'disabled'; ?>>حذف</button>
            </div>
        </div>
        <script>
        jQuery(function($){
            bindMedia(
                '#<?php echo $id; ?>-choose',
                '#<?php echo $id; ?>-remove',
                '#<?php echo $id; ?>_input',
                '#<?php echo $id; ?>-preview',
                '<?php echo esc_js($args['modalTitle']); ?>',
                '<?php echo esc_js($args['hint']); ?>',
            );
        });
        </script>
        <?php
    }
}
new PORTALIZE_MEDIA_FIELDS();

