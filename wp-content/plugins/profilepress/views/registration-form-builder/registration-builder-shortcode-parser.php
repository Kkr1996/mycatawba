<?php

class Registration_Builder_Shortcode_Parser
{
    /**
     * define all registration builder sub shortcode.
     */
    public static function initialize()
    {
        add_shortcode('reg-username', array(__CLASS__, 'reg_username'));
        add_shortcode('reg-password', array(__CLASS__, 'reg_password'));
        add_shortcode('reg-confirm-password', array(__CLASS__, 'reg_confirm_password'));
        add_shortcode('reg-email', array(__CLASS__, 'reg_email'));
        add_shortcode('reg-confirm-email', array(__CLASS__, 'reg_confirm_email'));
        add_shortcode('reg-website', array(__CLASS__, 'reg_website'));
        add_shortcode('reg-nickname', array(__CLASS__, 'reg_nickname'));
        add_shortcode('reg-display-name', array(__CLASS__, 'reg_display_name'));
        add_shortcode('reg-first-name', array(__CLASS__, 'reg_first_name'));
        add_shortcode('reg-last-name', array(__CLASS__, 'reg_last_name'));
        add_shortcode('reg-bio', array(__CLASS__, 'reg_bio'));
        add_shortcode('reg-avatar', array(__CLASS__, 'reg_avatar'));
        add_shortcode('reg-cpf', array(__CLASS__, 'reg_custom_profile_field'));
        add_shortcode('reg-submit', array(__CLASS__, 'reg_submit'));
        add_shortcode('reg-password-meter', array(__CLASS__, 'password_meter'));
        add_shortcode('reg-select-role', array(__CLASS__, 'select_role'));

        do_action('pp_register_registration_form_shortcode');
    }

    public static function GET_POST()
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Normalize unamed shortcode
     *
     * @param array $atts
     *
     * @return mixed
     */
    public static function normalize_attributes($atts)
    {
        if (is_array($atts)) {
            foreach ($atts as $key => $value) {
                if (is_int($key)) {
                    $atts[$value] = true;
                    unset($atts[$key]);
                }
            }
        }

        return $atts;
    }

    /**
     * Is field a required field?
     *
     * @param array $atts
     *
     * @return bool
     */
    public static function is_field_required($atts)
    {
        return isset($atts['required']) && ($atts['required'] === true || $atts['required'] == 'true');
    }

    /**
     * Rewrite custom field key to something more human readable.
     *
     * @param string $key field key
     *
     * @return string
     */
    public static function human_readable_field_key($key)
    {
        return ucfirst(str_replace('_', ' ', $key));
    }


    /**
     * parse the [reg-username] shortode
     *
     * @param array $atts
     *
     * @return string
     */
    public static function reg_username($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => true,
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_username_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_username']) ? 'value="' . esc_attr($_POST['reg_username']) . '"' : 'value="' . $atts['value'] . '"';
        $required = self::is_field_required($atts) ? 'required="required"' : null;

        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name='reg_username' type='text' $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_registration_username_field', $html, $atts);
    }

    /**
     * parse the [reg-password] shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public static function reg_password($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => true,
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_password_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_password']) ? 'value="' . esc_attr($_POST['reg_password']) . '"' : 'value="' . $atts['value'] . '"';
        $required = self::is_field_required($atts) ? 'required="required"' : null;
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name=\"reg_password\" type='password' $title $value $class $id $placeholder $other_atts_html $required >";
        $html .= '<input name="reg_password_present" type="hidden" value="true">';

        return apply_filters('pp_registration_password_field', $html, $atts);

    }

    /**
     * parse the [reg-confirm-password] shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public static function reg_confirm_password($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => true,
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_confirm_password_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_password2']) ? 'value="' . esc_attr($_POST['reg_password2']) . '"' : 'value="' . $atts['value'] . '"';
        $required = self::is_field_required($atts) ? 'required="required"' : null;
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name=\"reg_password2\" type='password' $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_registration_confirm_password_field', $html, $atts);


    }


    /**
     * Callback function for email
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_email($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => true,
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_email_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_email']) ? 'value="' . esc_attr($_POST['reg_email']) . '"' : 'value="' . $atts['value'] . '"';
        $required = self::is_field_required($atts) ? 'required="required"' : null;
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name='reg_email' type='email' $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_registration_email_field', $html, $atts);
    }

    /**
     * parse the [reg-confirm-password] shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public static function reg_confirm_email($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => true,
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_confirm_email_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_email2']) ? 'value="' . esc_attr($_POST['reg_email2']) . '"' : 'value="' . $atts['value'] . '"';
        $required = self::is_field_required($atts) ? 'required="required"' : null;
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name=\"reg_email2\" type='email' $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_registration_confirm_email_field', $html, $atts);


    }

    /**
     * Callback function for website
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_website($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_website_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_website']) ? esc_attr($_POST['reg_website']) : $atts['value'];
        $required = self::is_field_required($atts) ? 'required="required"' : '';
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name='reg_website' value='" . $value . "' type='text' $title $class $id $placeholder $other_atts_html $required >";
        // if field is required, add an hidden field
        if (self::is_field_required($atts)) {
            $value = apply_filters('pp_website_required_field', __('Website', 'profilepress'));
            $html .= "<input name='required-fields[reg_website]' type='hidden' value='$value'>";
        }

        return apply_filters('pp_registration_website_field', $html, $atts);

    }


    /**
     * Callback function for nickname
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_nickname($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_nickname_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_nickname']) ? esc_attr($_POST['reg_nickname']) : $atts['value'];
        $required = self::is_field_required($atts) ? 'required="required"' : '';
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name='reg_nickname' value='" . $value . "' type='text' $title $class $id $placeholder $other_atts_html $required >";
        // if field is required, add an hidden field
        if (self::is_field_required($atts)) {
            $value = apply_filters('pp_nickname_required_field', __('Nickname', 'profilepress'));
            $html .= "<input name='required-fields[reg_nickname]' type='hidden' value='$value'>";
        }

        return apply_filters('pp_registration_nickname_field', $html, $atts);

    }

    /**
     * Callback function for nickname
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_display_name($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_display_name_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_display_name']) ? esc_attr($_POST['reg_display_name']) : $atts['value'];
        $required = self::is_field_required($atts) ? 'required="required"' : '';
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name='reg_display_name' value='" . $value . "' type='text' $title $class $id $placeholder $other_atts_html $required >";
        // if field is required, add an hidden field
        if (self::is_field_required($atts)) {
            $value = apply_filters('pp_display_name_required_field', __('Display name', 'profilepress'));
            $html .= "<input name='required-fields[reg_display_name]' type='hidden' value='$value'>";
        }

        return apply_filters('pp_registration_display_name_field', $html, $atts);

    }


    /**
     * Callback function for first name
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_first_name($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_first_name_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_first_name']) ? esc_attr($_POST['reg_first_name']) : $atts['value'];
        $required = self::is_field_required($atts) ? 'required="required"' : '';

        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name=\"reg_first_name\" type='text' value='" . $value . "' $title $class $id $placeholder $other_atts_html $required >";
        // if field is required, add an hidden field
        if (self::is_field_required($atts)) {
            $value = apply_filters('pp_first_name_required_field', __('First name', 'profilepress'));
            $html .= "<input name='required-fields[reg_first_name]' type='hidden' value='$value'>";
        }

        return apply_filters('pp_registration_first_name_field', $html, $atts);

    }


    /**
     * Callback for last name
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_last_name($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_last_name_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_last_name']) ? esc_attr($_POST['reg_last_name']) : $atts['value'];
        $required = self::is_field_required($atts) ? 'required="required"' : '';
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name=\"reg_last_name\" value=\"$value\" type=\"text\" $title $class $placeholder $id $other_atts_html $required >";
        // if field is required, add an hidden field
        if (self::is_field_required($atts)) {
            $value = apply_filters('pp_last_name_required_field', __('Last name', 'profilepress'));
            $html .= "<input name='required-fields[reg_last_name]' type='hidden' value='$value'>";
        }

        return apply_filters('pp_registration_last_name_field', $html, $atts);

    }


    /**
     * Handles BIO
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_bio($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_bio_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['reg_bio']) ? esc_textarea($_POST['reg_bio']) : $atts['value'];
        $required = self::is_field_required($atts) ? 'required="required"' : '';
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<textarea name=\"reg_bio\" $title $class $placeholder $id $other_atts_html $required>$value</textarea>";
        // if field is required, add an hidden field
        if (self::is_field_required($atts)) {
            $value = apply_filters('pp_bio_required_field', __('Bio description', 'profilepress'));
            $html .= "<input name='required-fields[reg_bio]' type='hidden' value='$value'>";
        }

        return apply_filters('pp_registration_bio_field', $html, $atts);

    }


    /** Upload avatar field */
    public static function reg_avatar($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_avatar_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $required = self::is_field_required($atts) ? 'required="required"' : '';

        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input name=\"reg_avatar\" type=\"file\" $title $class $placeholder $id $other_atts_html $required >";

        // if field is required, add an hidden field
        if (self::is_field_required($atts)) {
            $value = apply_filters('pp_avatar_required_field', __('Profile picture', 'profilepress'));
            $html .= "<input name='required-fields[reg_avatar]' type='hidden' value='$value'>";
        }

        return apply_filters('pp_registration_avatar_field', $html, $atts);
    }


    /**
     * Handle custom registration | profile fields
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_custom_profile_field($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'required' => '',
                'key' => '',
                'type' => '',
                'placeholder' => '',
                'limit' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_cpf_field_atts', $atts);

        $key = esc_attr($atts['key']);
        $type = esc_attr($atts['type']);
        $class = 'class="' . esc_attr($atts['class']) . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $required = self::is_field_required($atts) ? 'required="required"' : '';

        $input_fields_array = array('text', 'password', 'email', 'tel', 'number', 'hidden');

        if (empty($key) || empty($type)) {
            return __('Field key or type is missing', 'profilepress');
        } elseif ($type == 'select') {

            $multiple = pp_is_select_field_multi_selectable($key) ? 'multiple' : null;
            $chosen_class_name = isset($multiple) && $multiple == 'multiple' ? 'pp_chosen ' : null;
            $class = 'class="' . $chosen_class_name . $atts['class'] . '"';
            $placeholder = 'data-placeholder="' . $atts['placeholder'] . '"';

            $select_tag_key = isset($multiple) && $multiple == 'multiple' ? "{$key}[]" : $key;
            $html = "<select name=\"$select_tag_key\" $placeholder $class $id $other_atts_html $required $multiple>";

            // get select option values
            $option_values = PROFILEPRESS_sql::get_field_option_values($key);

            // explode the options to an array
            if (isset($option_values[0])) {
                $option_values = explode(',', $option_values[0]);

                foreach ($option_values as $value) {
                    $value = trim($value);

                    // selected for <select>
                    $selected = is_array(@$_POST[$key]) && in_array($value, @$_POST[$key]) ? 'selected="selected"' : @selected(@$_POST[$key], $value, false);
                    $html .= "<option value=\"$value\" $selected>$value</option>";
                }
            }

            $html .= '</select>';
            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $value = apply_filters('pp_custom_required_field', self::human_readable_field_key($key), $key);
                $html .= "<input name='required-fields[$key]' type='hidden' value='$value'>";
            }

            if (isset($multiple) && $multiple == 'multiple') {
                $limit_count = absint($atts['limit']);
                $limit = (isset($limit_count)) ? "max_selected_options: $limit_count" : null;
                $html .= <<<SCRIPT
<script type='text/javascript'>
jQuery(function($) {
    $('select[name^="$key"].pp_chosen').chosen({width: '100%',$limit});
    // handle mobile selection edge cases.
    if(typeof pp_chosen_browser_is_supported == 'function' && pp_chosen_browser_is_supported() === false) {
        var selector = $('select[name^="$key"].pp_chosen');
        var last_valid_selection = null;
        selector.change(function(event) {
            if ($(this).val().length > $limit_count) {
                $(this).val(last_valid_selection);
            } else {
                last_valid_selection = $(this).val();
            }
        });
    }
});
</script>
SCRIPT;
            }
        } // if we are dealing with a radio button
        elseif ($type == 'radio') {

            // get select option values
            $option_values = PROFILEPRESS_sql::get_field_option_values($key);

            // explode the options to an array
            $option_values = explode(',', $option_values[0]);

            $html = '';
            foreach ($option_values as $value) {
                $value = trim($value);

                // checked for radio buttons
                $checked = @checked($_POST[$key], $value, false);

                $html .= "<input type='radio' name=\"$key\" value=\"$value\" id=\"$value\" $class $checked $other_atts_html $required>";
                $html .= "<label class=\"profilepress-reg-label css-labelz\" for=\"$value\">$value</label>";
            }
            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $value = apply_filters('pp_custom_required_field', self::human_readable_field_key($key), $key);
                $html .= "<input name='required-fields[$key]' type='hidden' value='$value'>";
            }
        } elseif ($type == 'agreeable') {

            $html = '';
            $field_label = html_entity_decode(PROFILEPRESS_sql::get_field_label($key));
            /** @todo do this for all custom fieleds that has static id value */
            $id = !empty($atts['id']) ? 'id="' . sanitize_text_field($atts['id']) . '"' : $key;

            // checked for checkbox
            $checked = @checked(@$_POST[$key], 'true', false);

            $html .= "<input type='checkbox' name=\"$key\" value=\"true\" $id $class $checked $other_atts_html $required>";
            $html .= "<label for=\"$key\">$field_label</label>";

            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $value = apply_filters('pp_custom_required_field', self::human_readable_field_key($key), $key);
                $html .= "<input name='required-fields[$key]' type='hidden' value='$value'>";
            }
        } elseif ($type == 'checkbox') {

            $multiple = pp_is_checkbox_field_multi_selectable($key) ? 'multiple' : null;
            $checkbox_tag_key = isset($multiple) && $multiple == 'multiple' ? "{$key}[]" : $key;

            // get select option values
            $option_values = PROFILEPRESS_sql::get_field_option_values($key);

            // explode the options to an array
            $option_values = explode(',', $option_values[0]);

            $html = '';
            foreach ($option_values as $value) {
                $value = trim($value);

                // checked for checkbox
                $checked = is_array(@$_POST[$key]) && in_array($value, @$_POST[$key]) ? 'checked="checked"' : @checked(@$_POST[$key], $value, false);

                $html .= "<input type='checkbox' name=\"$checkbox_tag_key\" value=\"$value\" id=\"$value\" $class $checked $other_atts_html $required>";
                $html .= "<label for=\"$value\">$value</label>";
            }
            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $value = apply_filters('pp_custom_required_field', self::human_readable_field_key($key), $key);
                $html .= "<input name='required-fields[$key]' type='hidden' value='$value'>";
            }
        } // if we are dealing with a text
        elseif (in_array($type, $input_fields_array)) {
            $value_attr = $atts['value'];

            $field_title = esc_attr($atts['title']);
            $title = "title=\"$field_title\"";

            $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
            $value = isset($_POST[$key]) ? 'value="' . esc_attr($_POST[$key]) . '"' : 'value="' . $value_attr . '"';

            $html = "<input name='" . $key . "' type='$type' $title $value $class $id $placeholder $other_atts_html $required>";
            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $value = apply_filters('pp_custom_required_field', self::human_readable_field_key($key), $key);
                $html .= "<input name='required-fields[$key]' type='hidden' value='$value'>";
            }

        } // if we are dealing with a textarea
        elseif ($type == 'textarea') {
            $value_attr = $atts['value'];

            $field_title = esc_attr($atts['title']);
            $title = "title=\"$field_title\"";

            $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
            $value = isset($_POST[$key]) ? esc_attr($_POST[$key]) : $value_attr;

            $html = "<textarea name=\"$key\" $title $class $placeholder $id $other_atts_html $required>$value</textarea>";
            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $value = apply_filters('pp_custom_required_field', self::human_readable_field_key($key), $key);
                $html .= "<input name='required-fields[$key]' type='hidden' value='$value'>";
            }

        } elseif ($type == 'country') {
            $value = isset($_POST[$key]) ? $_POST[$key] : $atts['value'];

            $field_title = esc_attr($atts['title']);
            $title = "title=\"$field_title\"";

            $countries = pp_array_of_world_countries();

            $html = "<select name='$key' $id $class $title $other_atts_html $required>";
            $html .= '<option value="">' . __('Select a country&hellip;', 'profilepress') . '</option>';

            foreach ($countries as $ckey => $cvalue) {
                $html .= '<option value="' . esc_attr($ckey) . '" ' . selected(sanitize_text_field($value), $ckey, false) . '>' . $cvalue . '</option>';
            }

            $html .= '</select>';
        } // if we are dealing with a date
        elseif ($type == 'date') {
            $value_attr = $atts['value'];

            $field_title = esc_attr($atts['title']);
            $title = "title=\"$field_title\"";
            $class = 'class="pp_datepicker ' . $atts['class'] . '"';

            $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
            $value = isset($_POST[$key]) ? 'value="' . esc_attr($_POST[$key]) . '"' : 'value="' . $value_attr . '"';

            $html = "<input name='" . $key . "' type='text' $title $value $class $id $placeholder $other_atts_html $required>";
            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $value = apply_filters('pp_custom_required_field', self::human_readable_field_key($key), $key);
                $html .= "<input name='required-fields[$key]' type='hidden' value='$value'>";
            }
            $html .= <<<SCRIPT
<script>
  jQuery(function($) {
    $( ".pp_datepicker" ).datepicker();
  });
  </script>
SCRIPT;

        } elseif ('file' == $type) {

            $field_title = esc_attr($atts['title']);
            $title = "title=\"$field_title\"";

            $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
            $value = isset($_POST[$key]) ? 'value="' . esc_attr($_POST[$key]) . '"' : 'value=""';

            $html = "<input name='" . $key . "' type='file' $title $value $class $id $placeholder $other_atts_html $required>";
            // if field is required, add an hidden field
            if (self::is_field_required($atts)) {
                $html .= "<input name='required-" . $key . "' type='hidden' value='true'>";
            }
        } else {
            $html = __('custom field not defined', 'profilepress');
        }

        return apply_filters('pp_registration_cpf_field', $html, $atts);
    }


    /**
     * Callback function for submit button
     *
     * @param $atts
     *
     * @return string
     */
    public static function reg_submit($atts)
    {
        $_POST = self::GET_POST();

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'name' => 'reg_submit',
                'id' => '',
                'value' => __('Sign Up', 'profilepress'),
                'title' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_submit_field_atts', $atts);

        $name = 'name="' . $atts['name'] . '"';
        $class = 'class="' . $atts['class'] . '"';
        $value = 'value="' . $atts['value'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $title = 'title="' . esc_attr($atts['title']) . '"';

        $html = "<input type='submit' $name $title $value $id $class $other_atts_html >";

        return apply_filters('pp_registration_submit_field', $html, $atts);
    }

    /**
     * Password strength meter field.
     * @see http://code.tutsplus.com/articles/using-the-included-password-strength-meter-script-in-wordpress--wp-34736
     */
    public static function password_meter($atts)
    {
        $_POST = self::GET_POST();

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'enforce' => 'true',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_password_meter_field_atts', $atts);

        wp_localize_script('password-strength-meter', 'pwsL10n', array(
            'empty' => __('Strength indicator'),
            'short' => __('Very weak'),
            'bad' => __('Weak'),
            'good' => _x('Medium', 'password strength'),
            'strong' => __('Strong'),
            'mismatch' => __('Mismatch'),
        ));

        ob_start(); ?>

        <?php if ('true' == $atts['enforce']) : ?>
        <input type="hidden" name="pp_enforce_password_meter" value="true">
    <?php endif; ?>
        <div id="pp-pass-strength-result" <?php echo 'class="' . $atts['class'] . '"' . $other_atts_html; ?>><?php _e('Strength indicator'); ?></div>
        <script type="text/javascript">
            var pass_strength = 0;
            jQuery(document).ready(function ($) {
                var password1 = $('input[name=reg_password]');
                var password2 = $('input[name=reg_password2]');
                var submitButton = $('input[name=reg_submit]');
                var strengthMeterId = $('#pp-pass-strength-result');

                $('body').on('keyup', 'input[name=reg_password], input[name=reg_password2]',
                    function (event) {
                        pp_checkPasswordStrength(password1, password2, strengthMeterId, submitButton, []);
                    }
                );

                if (password1.val() != '') {
                    // trigger 'keyup' event to check password strength when password field isn't empty.
                    $('body input[name=reg_password]').trigger('keyup');
                }

                submitButton.click(function () {
                    $('input[name=pp_enforce_password_meter]').val(pass_strength);
                });
            });

            function pp_checkPasswordStrength($pass1, $pass2, $strengthResult, $submitButton, blacklistArray) {
                var min_password_strength = <?php echo apply_filters('pp_min_password_strength', 4); ?>;
                var pass1 = $pass1.val();
                var pass2 = $pass2.val();

                <?php if('true' == $atts['enforce']) : ?>
                // Reset the form & meter
                $submitButton.attr('disabled', 'disabled').css("opacity", ".4");
                <?php endif; ?>
                $strengthResult.removeClass('short bad good strong');

                // Extend our blacklist array with those from the inputs & site data
                blacklistArray = blacklistArray.concat(wp.passwordStrength.userInputBlacklist());

                // Get the password strength
                var strength = wp.passwordStrength.meter(pass1, blacklistArray, pass2);

                // Add the strength meter results
                switch (strength) {
                    case 2:
                        $strengthResult.addClass('bad').html(pwsL10n.bad);
                        break;
                    case 3:
                        $strengthResult.addClass('good').html(pwsL10n.good);
                        if (min_password_strength === 3) {
                            pass_strength = 1;
                        }
                        break;
                    case 4:
                        $strengthResult.addClass('strong').html(pwsL10n.strong);
                        if (min_password_strength === 4) {
                            pass_strength = 1;
                        }
                        break;
                    case 5:
                        $strengthResult.addClass('short').html(pwsL10n.mismatch);
                        break;
                    default:
                        $strengthResult.addClass('short').html(pwsL10n.short);
                }

                // The meter function returns a result even if pass2 is empty,
                // enable only the submit button if the password is strong
                <?php if('true' == $atts['enforce']) : ?>
                if (min_password_strength <= strength) {
                    $submitButton.removeAttr('disabled').css("opacity", "");
                }
                <?php endif; ?>

                return strength;
            }
        </script>
        <?php
        return apply_filters('pp_registration_password_meter_field', ob_get_clean(), $atts);
    }

    public static function select_role($atts)
    {
        $_POST = self::GET_POST();

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'options' => '',
                'required' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_registration_select_role_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $required = self::is_field_required($atts) ? 'required="required"' : '';
        $title = 'title="' . esc_attr($atts['title']) . '"';

        if (!empty($atts['options'])) {
            $selectible_roles = array_map('trim', explode(',', $atts['options']));
        }

        if (isset($selectible_roles)) {
            $wp_roles = array_filter(pp_get_editable_roles(), function ($value) use ($selectible_roles) {
                // get the array key of the $value value.
                $key = array_search($value, pp_get_editable_roles());

                return in_array($key, $selectible_roles);
            });
        } else {
            $wp_roles = pp_get_editable_roles();
            unset($wp_roles['administrator']);
            unset($wp_roles['pending_users']);
        }

        $html = "<select name=\"reg_select_role\" $class $id $required $title $other_atts_html>";
        if (is_array($wp_roles)) {
            foreach ($wp_roles as $key => $value) {
                $selected = selected(@$_POST['reg_select_role'], $key, false);
                $label = $value['name'];
                $html .= "<option value='$key' id='select_role_$key' class='select_role_option' $selected>$label</option>";
            }
        }
        $html .= '</select>';

        return apply_filters('pp_registration_nickname_field', $html, $atts);

    }
}

Registration_Builder_Shortcode_Parser::initialize();