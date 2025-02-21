<?php

namespace App\GravityForms;

use GF_Field;

if (class_exists('GF_Field')) {
    class GF_Field_Test_Product extends GF_Field
    {
        public $type = 'test_product';
        public $testProductImage = '';

        public function get_form_editor_field_title()
        {
            return esc_attr__('Test Product Field', 'gravityforms');
        }

        public function get_form_editor_button()
        {
            return [
                'group' => 'advanced_fields',
                'text' => $this->get_form_editor_field_title(),
            ];
        }

        public function get_form_editor_field_settings()
        {
            return [
                'label_setting',
                'description_setting',
                'test_product_image_setting',
            ];
        }

        // Field rendering on the front end
        public function get_field_input($form, $value = '', $entry = null)
        {

            $image_url = rgar($this, 'testProductImage');

            ob_start();
            ?>
            <div class="gf-test-product">
                <?php if (!empty($image_url)): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="test product image" style="max-width: 300px;"/>
                <?php else: ?>
                    <p>No image selected</p>
                <?php endif; ?>
            </div>
            <?php
            return ob_get_clean();
        }

    }

    // Add custom image setting to the form editor
    add_action('gform_field_standard_settings', function ($position, $form_id) {
        if ($position === 50) {
            ?>
            <li class="test_product_image_setting field_setting">
                <label for="test_product_image" class="section_label">
                    <?php esc_html_e('Test Product Image', 'gravityforms'); ?>
                </label>
                <input type="text" id="test_product_image" class="fieldwidth-3"
                       onkeyup="SetFieldProperty('testProductImage', this.value);"/>
                <img style="margin-top: 20px; max-width:160px; display:block" id="test_product_image_preview" src=""
                     alt="test product image"/>
                <div style="margin-top: 20px;">
                    <button class="button" onclick="UploadImage(); return false;">Upload Image</button>
                    <button class="button remove-image" onclick="RemoveImage(); return false;" style="display:none;">
                        Remove Image
                    </button>
                </div>
            </li>
            <script>
                function UploadImage() {
                    const frame = wp.media({
                        title: 'Select or Upload Image',
                        button: {text: 'Use this image'},
                        multiple: false,
                    });
                    frame.on('select', function () {
                        const attachment = frame.state().get('selection').first().toJSON();
                        jQuery('#test_product_image').val(attachment.url).trigger('change');
                        SetFieldProperty('testProductImage', attachment.url);
                        jQuery('#test_product_image_preview').attr('src', attachment.url).show();
                        jQuery('.remove-image').show();

                    });
                    frame.open();
                }

                function RemoveImage() {
                    jQuery('#test_product_image').val('').trigger('change');
                    SetFieldProperty('testProductImage', '');
                    jQuery('#test_product_image_preview').hide();
                    jQuery('.remove-image').hide();
                }

                // Ensure saved field values are loaded in the form editor
                jQuery(document).on('gform_load_field_settings', function (event, field, form) {
                    const imageUrl = field.testProductImage || '';
                    jQuery("#test_product_image").val(imageUrl);
                    if (imageUrl) {
                        jQuery('#test_product_image_preview').attr('src', imageUrl).show();
                        jQuery('.remove-image').show();
                    } else {
                        jQuery('#test_product_image_preview').hide();
                        jQuery('.remove-image').hide();
                    }

                    if (imageUrl) {
                        jQuery('#test_product_image_preview').attr('src', imageUrl).show();
                        jQuery('.remove-image').show();
                    } else {
                        jQuery('#test_product_image_preview').hide();
                        jQuery('.remove-image').hide();
                    }
                });
            </script>
            <?php
        }
    }, 10, 2);

    // Enqueue wp image uploader
    add_action('admin_enqueue_scripts', function () {
        if (\GFForms::is_gravity_page()) {
            wp_enqueue_media();
        }
    });
}
