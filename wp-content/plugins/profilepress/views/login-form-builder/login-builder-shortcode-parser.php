<?php

/**
 * Parser for the child-shortcode of login form
 */
class Login_Builder_Shortcode_Parser
{

    /**
     * define all login builder sub shortcode.
     */
    function __construct()
    {
        add_shortcode('login-username', array($this, 'login_username'));

        add_shortcode('login-password', array($this, 'login_password'));

        add_shortcode('login-remember', array($this, 'login_remember'));

        add_shortcode('login-submit', array($this, 'login_submit'));

        do_action('pp_register_login_form_shortcode');
    }

    /**
     * parse the [login-username] shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    function login_username($atts)
    {
        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class'       => '',
                'id'          => '',
                'value'       => '',
                'title'       => 'Username',
                'placeholder' => '',
                'required'    => true

            ),
            $atts
        );

        $atts = apply_filters('pp_login_username_field_atts', $atts);

        $class       = !empty($atts['class']) ? 'class="' . $atts['class'] . '"' : null;
        $placeholder = !empty($atts['placeholder']) ? 'placeholder="' . $atts['placeholder'] . '"' : null;
        $id          = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value       = ! empty($atts['value']) ? 'value="' . $atts['value'] . '"' : 'value="' . esc_attr(@$_POST['login_username']) . '"';

        $title    = 'title="' . $atts['title'] . '"';
        $required = isset($atts['required']) && ($atts['required'] === true || $atts['required'] == 'true') ? 'required="required"' : null;

        $html = <<<HTML
<input name="login_username" type="text" {$value} {$title} $class $placeholder $id $other_atts_html $required>
HTML;
        return apply_filters('pp_login_username_field', $html, $atts);
    }

    /**
     * @param array $atts
     *
     * parse the [login-password] shortcode
     *
     * @return string
     */
    function login_password($atts)
    {
        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class'       => '',
                'id'          => '',
                'value'       => '',
                'title'       => 'Password',
                'placeholder' => '',
                'required'    => true
            ),
            $atts
        );

        $atts = apply_filters('pp_login_password_field_atts', $atts);

        $class       = 'class="' . $atts['class'] . '"';
        $placeholder = 'placeholder="' . $atts['placeholder'] . '"';

        $id          = !empty($atts['id']) ? 'id="' . $atts['id'] . '"' : null;
        $value       = ! empty($atts['value']) ? 'value="' . $atts['value'] . '"' : 'value="' . esc_attr(@$_POST['login_password']) . '"';
        $title       = 'title="' . $atts['title'] . '"';
        $required    = isset($atts['required']) && ($atts['required'] === true || $atts['required'] == 'true') ? 'required="required"' : null;

        $html = "<input name='login_password' type='password' $title $value $class $placeholder $id $other_atts_html $required>";

        return apply_filters('pp_login_password_field', $html, $atts);

    }

    /** Remember me checkbox */
    function login_remember($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id'    => '',
                'title' => ''
            ),
            $atts
        );

        $atts = apply_filters('pp_login_remember_field_atts', $atts);

        $class = 'class="' . $atts['class'] . '"';
        $id    = 'id="' . $atts['id'] . '"';
        $title = 'title="' . $atts['title'] . '"';

        $html = "<input name='login_remember' value='true' type='checkbox' $title $class $id $other_atts_html checked='checked'>";

        return apply_filters('pp_login_remember_field', $html, $atts);
    }


    /** Login submit button */
    function login_submit($atts)
    {

        // grab unofficial attributes
        $other_atts_html = pp_other_field_atts($atts);

        $atts = shortcode_atts(
            array(
                'class' => '',
                'id'    => '',
                'value' => 'Log In',
                'title' => '',
                'name'  => 'login_submit'
            ),
            $atts
        );

        $atts = apply_filters('pp_login_submit_field_atts', $atts);

        $name  = 'name="' . $atts['name'] . '"';
        $class = 'class="' . $atts['class'] . '"';
        $id    = 'id="' . $atts['id'] . '"';
        $value = ! empty($atts['value']) ? 'value="' . $atts['value'] . '"' : 'value="' . __('Log In', 'profilepress') . '"';

        $title = 'title="' . $atts['title'] . '"';

        $html = "<input data-pp-login-button='submit' type='submit' $name $title $class $id $value $other_atts_html>";

        return apply_filters('pp_login_submit_field', $html, $atts);
    }


    /** singleton poop */
    static function get_instance()
    {
        static $instance = false;

        if ( ! $instance) {
            $instance = new self;
        }

        return $instance;
    }
}

Login_Builder_Shortcode_Parser::get_instance();
