<?php

class Edit_User_Profile_Builder_Shortcode_Parser
{
    private static $current_user;

    /**
     * define all registration builder sub shortcode.
     */
    public function __construct()
    {
        add_action('init', array($this, 'get_current_user'));

        add_shortcode('edit-profile-username', array($this, 'edit_profile_username'));

        add_shortcode('edit-profile-password', array($this, 'edit_profile_password'));

        add_shortcode('edit-profile-confirm-password', array($this, 'edit_profile_confirm_password'));

        add_shortcode('edit-profile-password-meter', array(__CLASS__, 'password_meter'));

        add_shortcode('edit-profile-email', array($this, 'edit_profile_email'));

        add_shortcode('edit-profile-confirm-email', array($this, 'edit_profile_confirm_email'));

        add_shortcode('edit-profile-website', array($this, 'edit_profile_website'));

        add_shortcode('edit-profile-nickname', array($this, 'edit_profile_nickname'));

        add_shortcode('edit-profile-display-name', array($this, 'edit_profile_display_name'));

        add_shortcode('edit-profile-first-name', array($this, 'edit_profile_first_name'));

        add_shortcode('edit-profile-last-name', array($this, 'edit_profile_last_name'));

        add_shortcode('edit-profile-bio', array($this, 'edit_profile_bio'));

        add_shortcode('remove-user-avatar', array($this, 'remove_user_avatar'));

        add_shortcode('edit-profile-avatar', array($this, 'edit_profile_avatar'));

        add_shortcode('edit-profile-cpf', array($this, 'edit_profile_custom_profile_field'));

        add_shortcode('edit-profile-submit', array($this, 'edit_profile_submit'));

        do_action('pp_register_password_reset_form_shortcode');
    }


    /**
     * Normalize un-named shortcode
     *
     * @param $atts
     *
     * @return mixed
     */
    public static function normalize_attributes($atts)
    {
        foreach ($atts as $key => $value) {
            if (is_int($key)) {
                $atts[$value] = true;
                unset($atts[$key]);
            }
        }

        return $atts;
    }

    /** Get the currently logged user */
    public function get_current_user()
    {

        $current_user = wp_get_current_user();
        if ($current_user instanceof WP_User) {
            self::$current_user = $current_user;
        }
    }

    /**
     * parse the username shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public function edit_profile_username($atts)
    {

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Username',
                'required' => '',
                'placeholder' => 'Username',
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_username_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_username']) ? 'value="' . esc_attr($_POST['eup_username']) . '"' : 'value="' . self::$current_user->user_login . '"';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_username\" type=\"text\" $title $value $class $id $placeholder $other_atts_html disabled='disabled' >";

        return apply_filters('pp_edit_profile_username_field', $html, $atts);
    }

    /**
     * parse the password shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public function edit_profile_password($atts)
    {

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Password',
                'required' => '',
                'placeholder' => 'Password',
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_password_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_password']) ? 'value="' . esc_attr($_POST['eup_password']) . '"' : 'value=""';
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';
        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_password\" type='password' $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_edit_profile_password_field', $html, $atts);

    }

    /**
     * parse the password shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public function edit_profile_confirm_password($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => __('Confirm Password', 'profilepress'),
                'required' => '',
                'placeholder' => __('Confirm Password', 'profilepress'),
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_confirm_password_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_password2']) ? 'value="' . esc_attr($_POST['eup_password2']) . '"' : 'value=""';
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';
        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_password2\" type='password' $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_edit_profile_confirm_password_field', $html, $atts);
    }

    /**
     * Password strength meter field.
     */
    public static function password_meter($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'enforce' => 'false',
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_password_meter_field_atts', $atts);

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
                var password1 = $('input[name=eup_password]');
                var password2 = $('input[name=eup_password2]');
                var submitButton = $('input[name=eup_submit]');
                var strengthMeterId = $('#pp-pass-strength-result');

                $('body').on('keyup', 'input[name=eup_password], input[name=eup_password2]',
                    function (event) {
                        pp_checkPasswordStrength(password1, password2, strengthMeterId, submitButton, []);
                    }
                );

                if (password1.val() != '') {
                    // trigger 'keyup' event to check password strength when password field isn't empty.
                    $('body input[name=eup_password]').trigger('keyup');
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
        return apply_filters('pp_edit_profile_password_meter_field', ob_get_clean(), $atts);
    }

    /**
     * Callback function for email
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_email($atts)
    {

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Email Address',
                'required' => '',
                'placeholder' => 'Email Address',
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_email_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_email']) ? 'value="' . sanitize_email($_POST['eup_email']) . '"' : 'value="' . self::$current_user->user_email . '"';
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';
        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_email\" type=\"text\" $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_edit_profile_email_field', $html, $atts);

    }


    /**
     * Callback function for email
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_confirm_email($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => __('Confirm email Address', 'profilepress'),
                'required' => '',
                'placeholder' => __('Confirm email Address', 'profilepress'),
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_confirm_email_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_email2']) ? 'value="' . sanitize_email($_POST['eup_email2']) . '"' : 'value=""';
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';
        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_email2\" type=\"text\" $title $value $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_edit_profile_confirm_email_field', $html, $atts);

    }

    /**
     * Callback function for website
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_website($atts)
    {

        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Website',
                'required' => '',
                'placeholder' => 'Website',
            ),
            $atts
        );


        $atts = apply_filters('pp_edit_profile_website_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_website']) ? esc_attr($_POST['eup_website']) : self::$current_user->user_url;
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';
        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_website\" value='" . $value . "' type='text' $title $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_edit_profile_website_field', $html, $atts);
    }


    /**
     * Callback function for nickname
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_nickname($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Nickname',
                'required' => '',
                'placeholder' => 'Nickname',
            ),
            $atts
        );


        $atts = apply_filters('pp_edit_profile_nickname_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_nickname']) ? esc_attr($_POST['eup_nickname']) : self::$current_user->nickname;
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';
        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name='eup_nickname' value='" . $value . "' type='text' $title $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_edit_profile_nickname_field', $html, $atts);

    }


    /**
     * Callback for display name
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_display_name($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Display Name',
                'required' => '',
            ),
            $atts
        );


        $atts = apply_filters('pp_edit_profile_display_name_field_atts', $atts);

        $title = 'title="' . $atts['title'] . '"';
        $class = 'class="' . $atts['class'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';


        $profileuser = wp_get_current_user();
        $public_display = array();
        $public_display['display_nickname'] = $profileuser->nickname;
        $public_display['display_username'] = $profileuser->user_login;

        if (!empty($profileuser->first_name)) {
            $public_display['display_firstname'] = $profileuser->first_name;
        }

        if (!empty($profileuser->last_name)) {
            $public_display['display_lastname'] = $profileuser->last_name;
        }

        if (!empty($profileuser->first_name) && !empty($profileuser->last_name)) {
            $public_display['display_firstlast'] = $profileuser->first_name . ' ' . $profileuser->last_name;
            $public_display['display_lastfirst'] = $profileuser->last_name . ' ' . $profileuser->first_name;
        }

        if (!in_array(
            $profileuser->display_name,
            $public_display)
        ) // Only add this if it isn't duplicated elsewhere
        {
            $public_display = array('display_displayname' => $profileuser->display_name) + $public_display;
        }

        $public_display = array_map('trim', $public_display);
        $public_display = array_unique($public_display);

        $html = "<select name=\"eup_display_name\" $title $id $class $other_atts_html $required >";

        foreach ($public_display as $id => $item) {
            $selected = selected($profileuser->display_name, $item, false);
            $html .= "<option $selected> $item </option>";
        }

        $html .= '</select>';

        return apply_filters('pp_edit_profile_display_name_field', $html, $atts);
    }


    /**
     * Callback function for first name
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_first_name($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'First Name',
                'required' => '',
                'placeholder' => 'First Name',
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_first_name_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_first_name']) ? esc_attr($_POST['eup_first_name']) : self::$current_user->first_name;
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_first_name\" type='text' value='" . $value . "' $title $class $id $placeholder $other_atts_html $required >";

        return apply_filters('pp_edit_profile_first_name_field', $html, $atts);

    }


    /**
     * Callback for last name
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_last_name($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Last Name',
                'required' => '',
                'placeholder' => 'Last Name',
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_last_name_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_last_name']) ? esc_attr($_POST['eup_last_name']) : self::$current_user->last_name;
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"eup_last_name\" value=\"$value\" type=\"text\" $title $class $placeholder $id $other_atts_html $required >";

        return apply_filters('pp_edit_profile_last_name_field', $html, $atts);

    }


    /**
     * Handles user BIO
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_bio($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => 'Biographical Information',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );


        $atts = apply_filters('pp_edit_profile_bio_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = !empty($atts['placeholder']) ? $atts['placeholder'] : '';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['eup_bio']) ? esc_textarea($_POST['eup_bio']) : self::$current_user->description;
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<textarea name=\"eup_bio\" $title $class placeholder=\"$placeholder\" $id $required $other_atts_html >$value</textarea>";

        return apply_filters('pp_edit_profile_bio_field', $html, $atts);

    }


    public function edit_profile_avatar($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'required' => '',
                'placeholder' => '',
            ),
            $atts
        );


        $atts = apply_filters('pp_edit_profile_avatar_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';

        $field_title = $atts['title'];
        $title = isset($field_title) ? "title=\"$field_title\"" : '';

        $html = "<input name=\"eup_avatar\" type=\"file\" $title $class $placeholder $id $other_atts_html $required />";

        return apply_filters('pp_edit_profile_avatar_field', $html, $atts);
    }


    /**
     * Handle custom profile fields
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_custom_profile_field($atts)
    {
        $atts = self::normalize_attributes($atts);

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'required' => '',
                'key' => '',
                'type' => '',
                'placeholder' => '',
                'limit' => '',
            ),
            $atts
        );


        $atts = apply_filters('pp_edit_profile_cpf_field_atts', $atts);

        $key = sanitize_text_field($atts['key']);
        $type = sanitize_text_field($atts['type']);
        $class = 'class="' . sanitize_text_field($atts['class']) . '"';
        $id = !empty($atts['id']) ? 'id="' . sanitize_text_field($atts['id']) . '"' : null;
        $title = 'title="' . sanitize_text_field($atts['title']) . '"';
        $input_fields_array = array('text', 'password', 'email', 'tel', 'number', 'hidden');
        $required = (!empty($atts['required']) && $atts['required']) ? 'required="required"' : '';

        if (empty($key) || empty($type)) {
            return __('Field key and/or type is missing', 'profilepress');
        }

        if ($type == 'select') {
            $multiple = pp_is_select_field_multi_selectable($key) ? 'multiple' : null;
            $chosen_class_name = isset($multiple) && $multiple == 'multiple' ? 'pp_chosen ' : null;
            $class = 'class="' . $chosen_class_name . $atts['class'] . '"';
            $placeholder = 'data-placeholder="' . $atts['placeholder'] . '"';

            $select_tag_key = isset($multiple) && $multiple == 'multiple' ? "{$key}[]" : $key;
            $html = "<select name=\"$select_tag_key\" $placeholder $class $id $required $other_atts_html $multiple>";


            // get select option values
            $option_values = PROFILEPRESS_sql::get_field_option_values($key);

            // explode the options to an array
            if (isset($option_values[0])) {
                $option_values = explode(',', $option_values[0]);

                foreach ($option_values as $value) {
                    $value = htmlspecialchars_decode(trim($value));

                    // selected for <select>
                    if (is_array(@$_POST[$key]) && in_array($value, @$_POST[$key])) {
                        $selected = 'selected="selected"';
                    }
                    // !isset($_POST[ $key ] is called to not run the succeeding code if the form is submitted.
                    // to enable the select dropdown retain the submitted options when an error occur/ prevent the form from saving.
                    elseif (!isset($_POST[$key]) && is_array(self::$current_user->$key) && in_array($value, self::$current_user->$key)) {
                        $selected = 'selected="selected"';
                    } elseif (!isset($_POST[$key]) && !is_array(self::$current_user->$key) && $value == self::$current_user->$key) {
                        $selected = 'selected="selected"';
                    } // check for non array meta values
                    else {
                        $selected = null;
                    }

                    $html .= "<option value=\"$value\" $selected>$value</option>";
                }
            }

            $html .= '</select>';
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
        }

        if ($type == 'country') {
            $value = isset($_POST[$key]) ? sanitize_text_field($_POST[$key]) : self::$current_user->$key;

            $countries = pp_array_of_world_countries();

            $html = "<select name='$key' $id $class $title $required $other_atts_html>";
            $html .= '<option value="">' . __('Select a country&hellip;', 'profilepress') . '</option>';

            foreach ($countries as $ckey => $cvalue) {
                $html .= '<option value="' . esc_attr($ckey) . '" ' . selected($value, $ckey, false) . '>' . $cvalue . '</option>';
            }

            $html .= '</select>';
        }

        // if we are dealing with a radio button
        if ($type == 'radio') {

            // get select option values
            $option_values = PROFILEPRESS_sql::get_field_option_values($key);

            // explode the options to an array
            $option_values = explode(',', @$option_values[0]);

            $html = '';
            foreach ($option_values as $value) {
                $value = htmlspecialchars_decode(trim($value));

                // checked for radio buttons
                $checked = @checked(
                    isset($_POST[$key]) && !empty($_POST[$key]) ? $_POST[$key] : self::$current_user->$key,
                    $value,
                    false
                );

                $html .= "<input type=\"radio\" name=\"$key\" value=\"$value\" id=\"$value\" $class $title $checked $required $other_atts_html >";
                $html .= "<label class=\"profilepress-reg-label css-labelz\" for=\"$value\"> $value </label>";
            }
        }
        if ($type == 'agreeable') {
            $html = '';
            $field_label = html_entity_decode(PROFILEPRESS_sql::get_field_label($key));
            $value = 'true';
            $checked = @checked(
                !empty($_POST[$key]) ? $_POST[$key] : self::$current_user->$key,
                $value,
                false
            );

            /** @todo do this for all custom fieleds that has static id value */
            $id = !empty($atts['id']) ? 'id="' . sanitize_text_field($atts['id']) . '"' : $key;

            $html .= "<input type=\"hidden\" name=\"$key\" value=''>";
            $html .= "<input type=\"checkbox\" name=\"$key\" value=\"$value\" $id $title $class $checked $required $other_atts_html>";
            $html .= "<label for=\"$key\">$field_label</label>";
        }

        if ($type == 'checkbox') {
            $multiple = pp_is_checkbox_field_multi_selectable($key) ? 'multiple' : null;
            $checkbox_tag_key = isset($multiple) && $multiple == 'multiple' ? "{$key}[]" : $key;

            // get checkbkox option values
            $option_values = PROFILEPRESS_sql::get_field_option_values($key);

            // explode the options to an array
            $option_values = explode(',', $option_values[0]);

            $html = '';
            foreach ($option_values as $value) {
                $value = htmlspecialchars_decode(trim($value));

                $checked = pp_multicheckbox_checked($key, $value, self::$current_user);

                $html .= "<input type=\"hidden\" name=\"$checkbox_tag_key\" value=''>";
                $html .= "<input type=\"checkbox\" name=\"$checkbox_tag_key\" value=\"$value\" id=\"$value\" $title $class $checked $required $other_atts_html/>";
                $html .= "<label for=\"$value\">$value</label>";
            }
        }
        // if we are dealing with a text, number and similar
        if (in_array($type, $input_fields_array)) {

            $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
            $value = isset($_POST[$key]) ? 'value="' . esc_attr($_POST[$key]) . '"' : 'value="' . self::$current_user->$key . '"';

            $html = "<input name='" . $key . "' type='$type' $value $class $id $placeholder $title />";
        }

        // if we are dealing with a textarea
        if ($type == 'textarea') {

            $field_title = $atts['title'];
            $title = "title=\"$field_title\"";

            $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
            $value = isset($_POST[$key]) ? esc_attr($_POST[$key]) : self::$current_user->$key;

            $html = "<textarea name=\"$key\" $title $class $placeholder $id $required $other_atts_html>$value</textarea>";
        }


        // if we are dealing with a text
        if ($type == 'date') {
            $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
            $value = isset($_POST[$key]) ? 'value="' . esc_attr($_POST[$key]) . '"' : 'value="' . self::$current_user->$key . '"';
            $class = 'class="pp_datepicker ' . $atts['class'] . '"';

            $html = "<input name='" . $key . "' type='text' $value $class $id $placeholder $title />";
            $html .= <<<SCRIPT
<script>
  jQuery(function($) {
    $( ".pp_datepicker" ).datepicker();
  });
  </script>
SCRIPT;

        }

        if ('file' == $type) {
            $html = '';
            $user_upload_data = get_user_meta(self::$current_user->ID, 'pp_uploaded_files', true);
            // if the user uploads isn't empty and there exist a file with the custom field key.
            if (!empty($user_upload_data) && $filename = @$user_upload_data[$key]) {
                $link = PP_FILE_UPLOAD_URL . $filename;

                $html = "<div class='pp-user-upload'><a href='$link'>$filename</a></div>";
            } else {
                $html = '<div class="pp-user-upload">' . __('No file was found.', 'profilepress') . '</div>';
            }

            $html = apply_filters('pp_edit_profile_hide_file', $html);

            // $value $class $id $placeholder $title
            $html .= "<input name='" . $key . "' type='file' $title $class $id $required $other_atts_html>";
        }

        /**
         * @param string $html html code of the field.
         * @param string $type custom field type.
         * @param string $key custom field key.
         * @param string $value custom field form value (saved in database).
         * @param string self::$current_user WP_User object of the currently logged in user.
         */

        return apply_filters('pp_edit_profile_cpf_field', $html, $type, $key, $value, self::$current_user, $atts);
    }


    /**
     * Remove a user avatar
     *
     * @param $atts
     *
     * @return string
     */
    public function remove_user_avatar($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'title' => '',
                'label' => __('Delete Avatar', 'profilepress'),
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_remove_avatar_button_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $label = !empty($atts['label']) ? $atts['label'] : null;
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $title = 'title="' . $atts['title'] . '"';

        // ensure a profile avatar for the user is available before the remove button gets displayed
        $avatar_slug = get_user_meta(self::$current_user->ID, 'pp_profile_avatar', true);
        if (!empty($avatar_slug)) {
            $button = "<button type=\"submit\" id=\"pp-del-avatar\" name=\"eup_remove_avatar\" value=\"removed\" $class $id $title $other_atts_html>$label</button>";

            return apply_filters('pp_edit_profile_remove_avatar_button', $button, $atts);
        }
    }


    /**
     * Callback function for submit button
     *
     * @param $atts
     *
     * @return string
     */
    public function edit_profile_submit($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'name' => 'eup_submit',
                'id' => '',
                'value' => 'Save Changes',
                'title' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_edit_profile_submit_field_atts', $atts);

        $name = 'name="' . $atts['name'] . '"';
        $class = 'class="' . $atts['class'] . '"';
        $value = 'value="' . $atts['value'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input type='submit' $name $title $value $id $class $other_atts_html />";

        return apply_filters('pp_edit_profile_submit_field', $html, $atts);
    }


    /** Singleton instance */
    public static function get_instance()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self;
        }

        return $instance;
    }
}


Edit_User_Profile_Builder_Shortcode_Parser::get_instance();