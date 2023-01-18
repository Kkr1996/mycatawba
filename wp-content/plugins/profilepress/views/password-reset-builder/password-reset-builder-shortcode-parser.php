<?php

class Password_Reset_Builder_Shortcode_Parser
{
    /**
     * define all registration builder sub shortcode.
     */
    function __construct()
    {
        add_shortcode('user-login', array($this, 'profilepress_user_login'));
        add_shortcode('reset-submit', array($this, 'profilepress_submit_button'));

        add_shortcode('enter-password', array($this, 'enter_password'));
        add_shortcode('re-enter-password', array($this, 're_enter_password'));
        add_shortcode('password-reset-submit', array($this, 'password_reset_submit'));

        add_shortcode('reset-password-meter', array($this, 'password_meter'));

        do_action('pp_register_password_reset_form_shortcode');
    }

    /**
     * parse the [user-login] shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public function profilepress_user_login($atts)
    {
        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => 'Username or Email',
                'placeholder' => 'Username or Email',
            ),
            $atts
        );

        $atts = apply_filters('pp_password_reset_username_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
         $id   = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['user_login']) ? 'value="' . esc_attr($_POST['user_login']) . '"' : 'value=""';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"user_login\" type='text' $title $value $class $id $placeholder $other_atts_html required='required'/>";

        return apply_filters('pp_password_reset_username_field', $html, $atts);
    }

    /**
     * Password reset submit button.
     *
     * @param $atts array shortcode param
     *
     * @return string HTML submit button
     */
    function profilepress_submit_button($atts)
    {
        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => __('Get New Password', 'profilepress'),
                'title' => '',
                'name' => 'password_reset_submit',
            ),
            $atts
        );


        $atts = apply_filters('pp_password_reset_submit_field_atts', $atts);

        $name = 'name="' . $atts['name'] . '"';
        $class = 'class="' . $atts['class'] . '"';
        $value = 'value="' . $atts['value'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : '';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input type='submit' $name $title $value $id $class $other_atts_html />";

        return apply_filters('pp_password_reset_submit_field', $html, $atts);
    }


    /**
     * parse the [enter-password] shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    function enter_password($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_password_reset_handler_password1_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
         $id   = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['password1']) ? 'value="' . esc_attr($_POST['password1']) . '"' : 'value=""';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"password1\" type='password' $title $value $class $id $placeholder $other_atts_html autocomplete='off'>";

        return apply_filters('pp_password_reset_handler_password1_field', $html, $atts);
    }


    /**
     * parse the [re-enter-password] shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    function re_enter_password($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => '',
                'title' => '',
                'placeholder' => '',
            ),
            $atts
        );

        $atts = apply_filters('pp_password_reset_handler_password2_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';
         $id   = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value = isset($_POST['password2']) ? 'value="' . esc_attr($_POST['password2']) . '"' : 'value=""';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name=\"password2\" type='password' $title $value $class $id $placeholder $other_atts_html autocomplete='off'>";

        return apply_filters('pp_password_reset_handler_password2_field', $html, $atts);
    }

    /**
     * Password strength meter field for password reset handler form.
     * @see http://code.tutsplus.com/articles/using-the-included-password-strength-meter-script-in-wordpress--wp-34736
     */
    public static function password_meter($atts)
    {
        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'enforce' => 'true',
            ),
            $atts
        );

        $atts = apply_filters('pp_password_reset_handler_meter_field_atts', $atts);

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
                var password1 = $('input[name=password1]');
                var password2 = $('input[name=password2]');
                var submitButton = $('input[name=reset_password]');
                var strengthMeterId = $('#pp-pass-strength-result');

                $('body').on('keyup', 'input[name=password1], input[name=password2]',
                    function (event) {
                        pp_checkPasswordStrength(password1, password2, strengthMeterId, submitButton, []);
                    }
                );

                if (password1.val() != '') {
                    // trigger 'keyup' event to check password strength when password field isn't empty.
                    $('body input[name=password1]').trigger('keyup');
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

    /**
     * Password reset handler submit button.
     *
     * @param $atts array shortcode param
     *
     * @return string HTML submit button
     */
    function password_reset_submit($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id' => '',
                'value' => 'Get New Password',
                'title' => '',
                'name' => 'reset_password',
            ),
            $atts
        );

        $atts = apply_filters('pp_password_reset_handler_submit_field_atts', $atts);

        $name = 'name="' . $atts['name'] . '"';
        $class = 'class="' . $atts['class'] . '"';
        $value = 'value="' . $atts['value'] . '"';
        $id = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : '';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input type='submit' $name $title $value $id $class $other_atts_html />";

        return apply_filters('pp_password_reset_handler_submit_field', $html, $atts);
    }


    /** Singleton poop */
    static function get_instance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self;
        }

        return $instance;
    }
}

Password_Reset_Builder_Shortcode_Parser::get_instance();
