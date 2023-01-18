<?php

/**
 * Add extra field to the bottom of user profile.
 */
class Extra_Profile_field_bottom_placement
{

    static private $instance;

    public $upload_errors;

    /**
     * add the extra field and update to DB
     */
    function __construct()
    {
        add_action('show_user_profile', array($this, 'profile_user_admin'));
        add_action('edit_user_profile', array($this, 'profile_user_admin'));

        add_action('personal_options_update', array($this, 'update_user_admin'));
        add_action('edit_user_profile_update', array($this, 'update_user_admin'));

        add_action('user_profile_update_errors', array($this, 'file_upload_errors'), 10, 3);

        add_action('user_edit_form_tag', array($this, 'edit_form_type'));

    }

    /**
     * Add multipart/form-data to wordpress profile admin page
     */
    public function edit_form_type()
    {
        echo ' enctype="multipart/form-data"';
    }


    public function profile_user_admin($user)
    {
        if (!PROFILEPRESS_sql::sql_user_admin_profile()) {
            return;
        }
        ?>
        <h3><?php echo apply_filters('pp_custom_field_header', __('Other Information', 'profilepress')); ?></h3>
        <table class="form-table">
            <?php
            foreach (PROFILEPRESS_sql::sql_user_admin_profile() as $extra_field) {
                // skip woocommerce core billing / shipping fields added to wordpress profile admin page.
                if (in_array($extra_field->field_key, $this->woocommerce_billing_shipping_fields())) continue;

                // skip terms and condition fields.
                if ($extra_field->type == 'agreeable') continue;

                $input_fields_array = array('text', 'password', 'email', 'tel', 'number', 'hidden');

                if (in_array($type = $extra_field->type, $input_fields_array)) {
                    ?>
                    <tr>
                        <th>
                            <label for="<?php echo $extra_field->field_key; ?>"><?php _e($extra_field->label_name); ?></label>
                        </th>
                        <td>
                            <input type="<?php echo $type; ?>" name="<?php echo esc_attr($extra_field->field_key); ?>" id="<?php echo $extra_field->field_key; ?>" value="<?php echo esc_attr(get_the_author_meta($extra_field->field_key, $user->ID)); ?>" class="regular-text"/>
                            <p class="description"><?php _e($extra_field->description, 'profilepress'); ?></p>
                        </td>
                    </tr>

                    <?php
                } elseif ($extra_field->type == 'date') {
                    ?>
                    <tr>
                        <th>
                            <label for="<?php echo $extra_field->field_key; ?>"><?php _e($extra_field->label_name); ?></label>
                        </th>
                        <td>
                            <input type="text" name="<?php echo esc_attr($extra_field->field_key); ?>" id="<?php echo $extra_field->field_key; ?>" value="<?php echo esc_attr(get_the_author_meta($extra_field->field_key, $user->ID)); ?>" class="pp_datepicker regular-text">

                            <p class="description"><?php _e($extra_field->description, 'profilepress'); ?></p>
                            <script>
                                jQuery(function ($) {
                                    $(".pp_datepicker").datepicker();
                                });
                            </script>
                        </td>
                    </tr>

                    <?php
                } elseif ($extra_field->type == 'country') {
                    $countries = pp_array_of_world_countries();
                    $value = esc_attr(get_the_author_meta($extra_field->field_key, $user->ID));
                    ?>
                    <tr>
                        <th>
                            <label for="<?php echo $extra_field->field_key; ?>"><?php _e($extra_field->label_name); ?></label>
                        </th>
                        <td>
                            <select name="<?php echo $extra_field->field_key; ?>">
                                <option value=""><?php __('Select a country&hellip;', 'profilepress'); ?></option>
                                <?php foreach ($countries as $ckey => $cvalue) : ?>
                                    <option value="<?php esc_attr_e($ckey); ?>" <?php selected($value, $ckey); ?>><?php echo $cvalue; ?> </option>';
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <?php
                } elseif ($extra_field->type == 'textarea') {
                    ?>
                    <tr>
                        <th>
                            <label for="<?php echo $extra_field->field_key; ?>"><?php _e($extra_field->label_name); ?></label>
                        </th>
                        <td>
                            <textarea rows="5" name="<?php echo $extra_field->field_key; ?>"><?php echo esc_attr(get_the_author_meta($extra_field->field_key, $user->ID)); ?></textarea>

                            <p class="description"><?php _e($extra_field->description, 'profilepress'); ?></p>
                        </td>
                    </tr>
                    <?php
                } elseif ($extra_field->type == 'radio') {
                    $radio_buttons = explode(',', $extra_field->options); ?>
                    <tr>
                        <th>
                            <?php _e($extra_field->label_name); ?>
                        </th>
                        <td>
                            <?php foreach ($radio_buttons as $radio_button) {
                                // remove whitespace after exploding value to array
                                $radio_button = trim($radio_button); ?>
                                <input id="<?php echo $radio_button; ?>" type="radio" name="<?php echo esc_attr($extra_field->field_key); ?>" value="<?php echo esc_attr($radio_button); ?>" <?php checked((get_the_author_meta($extra_field->field_key, $user->ID)), $radio_button); ?> />
                                <label for="<?php echo esc_attr($radio_button); ?>"><?php echo esc_attr($radio_button); ?></label>
                                <br/>
                            <?php } ?>
                            <p class="description"><?php _e($extra_field->description, 'profilepress'); ?></p>
                        </td>
                    </tr>

                    <?php
                } elseif ($extra_field->type == 'checkbox') {
                    $checkbox_values = explode(',', $extra_field->options);

                    $key = $extra_field->field_key;
                    $is_multi_selectable = pp_is_checkbox_field_multi_selectable($key);
                    $checkbox_tag_key = $is_multi_selectable ? "{$key}[]" : $key;
                    $cpf_saved_data = get_the_author_meta($extra_field->field_key, $user->ID);
                    ?>
                    <tr>
                        <th>
                            <?php _e($extra_field->label_name); ?>
                        </th>
                        <td>
                            <?php foreach ($checkbox_values as $checkbox_value) {
                                // remove whitespace after exploding value to array
                                $checkbox_value = trim($checkbox_value);

                                // if data is for multi select dropdown
                                if (is_array($cpf_saved_data) && in_array($checkbox_value, $cpf_saved_data)) {
                                    $checked = 'checked="checked"';
                                } // if data is a single checkbox
                                elseif (!$is_multi_selectable && !is_array($cpf_saved_data) && $checkbox_value == $cpf_saved_data) {
                                    $checked = 'checked="checked"';
                                } else {
                                    $checked = null;
                                }
                                ?>

                                <input id="<?php echo $checkbox_value; ?>" type="checkbox" name="<?php echo $checkbox_tag_key; ?>" value="<?php echo esc_attr($checkbox_value); ?>" <?php echo $checked; ?> />
                                <label for="<?php echo esc_attr($checkbox_value); ?>"><?php echo esc_attr($checkbox_value); ?></label>
                                <br/>
                            <?php } ?>
                            <p class="description"><?php _e($extra_field->description, 'profilepress'); ?></p>
                        </td>
                    </tr>

                    <?php
                } elseif ($extra_field->type == 'select') {
                    $select_options_values = explode(',', $extra_field->options); ?>
                    <tr>
                        <th>
                            <?php _e($extra_field->label_name); ?>
                        </th>
                        <td>
                            <?php
                            $key = $extra_field->field_key;
                            $is_multi_selectable = pp_is_select_field_multi_selectable($key);
                            $chosen_class_name = $is_multi_selectable ? 'pp_chosen ' : null;
                            $class = "class='$chosen_class_name'";
                            $select_tag_key = $is_multi_selectable ? "{$key}[]" : $key;
                            $multiple = $is_multi_selectable ? 'multiple' : null;

                            ?>
                            <?php
                            $cpf_saved_data = get_the_author_meta($extra_field->field_key, $user->ID);

                            echo "<select $class name='$select_tag_key' $multiple>";
                            foreach ($select_options_values as $options_value) {

                                // remove whitespace after exploding value to array
                                $options_value = trim($options_value);

                                // selected for <select>

                                // if data is for multi select dropdown
                                if (is_array($cpf_saved_data) && in_array($options_value, $cpf_saved_data)) {
                                    $selected = 'selected="selected"';
                                } // if data is not multi select dropdown but a single selection dropdown
                                elseif (!$is_multi_selectable && !is_array($cpf_saved_data) && $options_value == $cpf_saved_data) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = null;
                                }
                                ?>
                                <option value="<?php echo $options_value; ?>" <?php echo $selected; ?>><?php echo $options_value; ?></option>

                            <?php } ?>
                            </select>
                            <?php
                            if ($is_multi_selectable === true) : ?>
                                <script>
                                    jQuery(function ($) {
                                        $(".pp_chosen").chosen({width: "350px"});
                                    });
                                </script>

                            <?php endif; ?>

                            <p class="description"><?php echo esc_attr($extra_field->description); ?></p>
                        </td>
                    </tr>

                    <?php
                } elseif ($extra_field->type == 'file') { ?>
                    <tr>
                        <th>
                            <?php _e($extra_field->label_name); ?>
                        </th>
                        <td>
                            <?php
                            $user_upload_data = get_user_meta($user->ID, 'pp_uploaded_files', true);
                            // if the user uploads isn't empty and there exist a file with the custom field key.
                            if (!empty($user_upload_data) && $filename = @$user_upload_data[$extra_field->field_key]) {
                                $link = PP_FILE_UPLOAD_URL . $filename;

                                echo "<p><a href='$link'>$filename</a></p>";
                            } else {
                                echo '<p>' . __('No file was found.', 'profilepress') . '</p>';
                            }
                            ?>
                            <p><input name="<?php echo $extra_field->field_key; ?>" type="file"></p>

                            <p class="description"><?php echo esc_attr($extra_field->description, 'profilepress'); ?></p>
                        </td>
                    </tr>

                    <?php
                }
            } ?>
        </table>
        <?php
    }


    /**
     * Update user profile info.
     *
     * @param int $user_id
     */
    public function update_user_admin($user_id)
    {
        if (current_user_can('edit_user', $user_id)) {

            foreach (PROFILEPRESS_sql::sql_user_admin_profile() as $user_admin) {
                $field_key = $user_admin->field_key;
                $field_data = $_POST[$field_key];

                $field_value = is_array($field_data) ? array_map('sanitize_text_field', $field_data) : sanitize_text_field($field_data);

                update_user_meta(
                    $user_id,
                    $user_admin->field_key,
                    $field_value
                );

                do_action('pp_after_custom_field_update', $field_key, $field_value, $user_id);
            }

            // update file uploads
            $uploads = PP_File_Uploader::init();
            $upload_errors = '';
            foreach ($uploads as $field_key => $uploaded_filename_or_wp_error) {
                if (is_wp_error($uploads[$field_key])) {
                    $upload_errors .= $uploads[$field_key]->get_error_message() . '<br/>';
                    // save the error in a global state
                    $this->upload_errors = $upload_errors;
                }
            }

            if (empty($upload_errors)) {
                // we get the old array of stored file for the user
                $old = get_user_meta($user_id, 'pp_uploaded_files', true);
                $old = !empty($old) ? $old : array();

                // we loop through the array of newly uploaded files and remove any file (unsetting the file array key)
                // that isn't be updated i.e if the field is left empty, unsetting it prevent update_user_meta
                // fom overriding it.
                // we then merge the old and new uploads before saving the data to user meta table.
                foreach ($uploads as $key => $value) {
                    if (is_null($value) || empty($value)) {
                        unset($uploads[$key]);
                    }
                }

                update_user_meta($user_id, 'pp_uploaded_files', array_merge($old, $uploads));
            }
        }
    }

    /**
     * Output generated files upload errors.
     *
     * @param string $errors
     * @param string $update
     * @param WP_USer $user
     */
    public function file_upload_errors($errors, $update, $user)
    {
        if (empty($this->upload_errors)) {
            return;
        }
        $errors->add('file_upload_err', $this->upload_errors);
    }

    /**
     * Array of WooCommerce billing and shipping fields added to default wordpress profile..
     *
     * @return array
     */
    public function woocommerce_billing_shipping_fields()
    {
        return array(
            'billing_first_name',
            'billing_last_name',
            'billing_company',
            'billing_address_1',
            'billing_address_2',
            'billing_city',
            'billing_postcode',
            'billing_country',
            'billing_state',
            'billing_phone',
            'billing_email',
            'shipping_first_name',
            'shipping_last_name',
            'shipping_company',
            'shipping_address_1',
            'shipping_address_2',
            'shipping_city',
            'shipping_country',
            'shipping_state'
        );
    }

    public static function GetInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}


Extra_Profile_field_bottom_placement::GetInstance();