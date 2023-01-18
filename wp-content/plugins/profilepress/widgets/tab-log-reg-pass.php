<?php
/**
 * ProfilePress tabbed widget
 */

require_once CLASSES . '/tabbed-widget-dependency.php';


class Tabbed_login_reg_passkey extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pp_tabbed_widget', // Base ID
            __('ProfilePress Tabbed Widget', 'profilepress'), // Name
            array(
                'description' => __('A Tabbed Login, Register and Lost Password Widget',
                    'profilepress'
                ),
            ),
            array('width' => 400, 'height' => 350)// Args
        );
    }


    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);

        echo '<style>';
        echo $instance['tabbed_css'];
        echo '</style>';

        echo $args['before_widget'];

        if ( ! is_user_logged_in() && ! empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if (isset($_POST['tabbed_login_submit'])) {
            $widget_status = @Tabbed_widget_dependency::login(
                esc_attr($_POST['tabbed-login-name']),
                esc_attr($_POST['tabbed-login-password']),
                esc_attr($_POST['tabbed-login-remember-me'])
            );
        } elseif (isset($_POST['tabbed_reset_passkey'])) {
            $widget_status = @Tabbed_widget_dependency::retrieve_password_process($_POST['tabbed-user-login']);
        } elseif (isset($_POST['tabbed_reg_submit'])) {
            $widget_status = @Tabbed_widget_dependency::registration($_POST['tabbed-reg-username'],
                $_POST['tabbed-reg-password'],
                $_POST['tabbed-reg-email'],
                $instance['auto_login_after_reg']);
        }

        if (isset($widget_status)) {
            echo '<div class="pp-tab-status">', $widget_status, '</div>';
        }

        if ( ! is_user_logged_in()) {
            ?>
            <div class="flat-form">
                <ul class="pp-tab-widget">
                    <li>
                        <a href="#pp-login" class="active"><?php echo apply_filters('pp_tab_login_text', __('Login', 'profilepress')); ?></a>
                    </li>
                    <li>
                        <a href="#pp-register"><?php echo apply_filters('pp_tab_register_text', __('Register', 'profilepress')); ?></a>
                    </li>
                    <li>
                        <a href="#pp-reset"><?php echo apply_filters('pp_tab_password_reset_text', __('Forgot?', 'profilepress')); ?></a>
                    </li>
                </ul>
                <div id="pp-login" class="form-action show">
                    <div class="heading"><?php echo isset($instance['login_text']) ? $instance['login_text'] : 'Have an account?'; ?></div>
                    <form data-pp-form-submit="login" method="post" action="<?php echo esc_url_raw($_SERVER['REQUEST_URI']); ?>">
                        <ul class="tab-widget" style="list-style: none">
                            <li>
                                <?php
                                $db_settings_data = get_option('pp_extra_login_with_email');
                                // if login by email is activated, modify the placeholder
                                $login_placeholder = isset($db_settings_data) && $db_settings_data == 'active' ? 'Username or Email' : 'Username'; ?>
                                <input type="hidden" name="is-pp-tab-widget" value="true">
                                <input type="hidden" name="auto-login-after-reg" value="<?php echo $instance['auto_login_after_reg']; ?>">
                                <input type="text" name="tabbed-login-name" value="<?php echo(isset($_POST['tabbed-login-name']) ? $_POST['tabbed-login-name'] : ''); ?>" placeholder="<?php echo $login_placeholder; ?>" required/>
                            </li>
                            <li>
                                <input name="tabbed-login-password" value="<?php echo(isset($_POST['tabbed-login-password']) ? $_POST['tabbed-login-password'] : ''); ?>" type="password" placeholder="Password" required/>
                            </li>
                            <li class="remember-login">
                                <input class="flat-checkbox" id="remember-login" name="tabbed-login-remember-me" type="checkbox" value="true">
                                <label for="remember-login" class="css-label lite-cyan-check">Remember Me</label>
                            </li>
                            <li>
                                <input name="tabbed_login_submit" type="submit" value="Log In" class="tb-button"/>
                            </li>
                        </ul>
                    </form>
                </div>
                <!--/#login.form-action-->
                <div id="pp-register" class="form-action hide">
                    <div class="heading"><?php echo isset($instance['reg_text']) ? $instance['reg_text'] : 'Don\'t have an account?'; ?></div>

                    <div class="tab-widget">
                        <form data-pp-form-submit="signup" method="post" action="<?php echo esc_url_raw($_SERVER['REQUEST_URI']); ?>">
                            <ul class="tab-widget" style="list-style: none">
                                <li>
                                    <input type="hidden" name="is-pp-tab-widget" value="true">
                                    <input type="text" name="tabbed-reg-username" placeholder="Username" value="<?php echo(isset($_POST['tabbed-reg-username']) ? $_POST['tabbed-reg-username'] : ''); ?>" required/>
                                </li>
                                <li>
                                    <input type="email" name="tabbed-reg-email" placeholder="Email" value="<?php echo(isset($_POST['tabbed-reg-email']) ? $_POST['tabbed-reg-email'] : ''); ?>" required/>
                                </li>
                                <li>
                                    <input type="password" name="tabbed-reg-password" placeholder="Password" value="<?php echo(isset($_POST['tabbed-reg-password']) ? $_POST['tabbed-reg-password'] : ''); ?>" required/>
                                </li>

                                <li>
                                    <input name="tabbed_reg_submit" type="submit" value="Sign Up" class="tb-button"/>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>

                <div id="pp-reset" class="form-action hide">
                    <div class="heading"><?php echo isset($instance['lostp_text']) ? $instance['lostp_text'] : 'Forgot Password?'; ?></div>

                    <div class="tab-widget">
                        <form data-pp-form-submit="passwordreset" method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
                            <ul class="tab-widget" style="list-style: none">
                                <li>
                                    <input name="tabbed-user-login" value="<?php echo(isset($_POST['tabbed-user-login']) ? $_POST['tabbed-user-login'] : ''); ?>" type="text" placeholder="Username or E-mail:" required/>
                                    <input type="hidden" name="is-pp-tab-widget" value="true">
                                </li>
                                <li>
                                    <input name="tabbed_reset_passkey" type="submit" value="Send" class="tb-button"/>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>

            </div>
            <?php
        } else {
            /* Get the current user’s data. */
            $user_data = wp_get_current_user();
            ?>

            <div class="tile"> <?php
                echo '<a href="' . pp_profile_url() . '"><div class="demo-download">';
                echo get_avatar($user_data->ID, 300);
                echo '</div></a>';
                ?>
                <h3 class="tile-title"><?php printf(__('Welcome %s', 'profilepress'), ucfirst($user_data->display_name)); ?></h3>
                <br/>

                <p>
                    <a class="btn btn-inverse" href="<?php echo pp_edit_profile_url(); ?>"><?php _e('Edit your Profile', 'profilepress'); ?></a>
                </p>

                <p>
                    <a class="btn btn-inverse" href="<?php echo wp_logout_url(); ?>"><?php _e('Log Out', 'profilepress'); ?></a>
                </p>

            </div>

            <?php

        } ?>

        <script>
            jQuery(document).ready(function ($) {
                $('.pp-tab-widget').on('click', 'li a', function (e) {
                    e.preventDefault();
                    var $tab = $(this),
                        href = $tab.attr('href');

                    $('.active').removeClass('active');
                    $tab.addClass('active');

                    $('.show')
                        .removeClass('show')
                        .addClass('hide')
                        .hide();

                    $(href)
                        .removeClass('hide')
                        .addClass('show')
                        .hide()
                        .fadeIn(550);
                });
            });

        </script>
        <?php
        echo $args['after_widget'];

    }


    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Login / Sign up', 'profilepress');
        }

        if (isset($instance['login_text'])) {
            $login_text = $instance['login_text'];
        } else {
            $login_text = __('Have an account?', 'profilepress');
        }

        if (isset($instance['reg_text'])) {
            $reg_text = $instance['reg_text'];
        } else {
            $reg_text = __('Don\'t have an account?', 'profilepress');
        }

        if (isset($instance['lostp_text'])) {
            $lostp_text = $instance['lostp_text'];
        } else {
            $lostp_text = __('Forgot Password?', 'profilepress');
        }

        if (isset($instance['auto_login_after_reg'])) {
            $auto_login_after_reg = $instance['auto_login_after_reg'];
        } else {
            $db_settings_data     = get_option('pp_settings_data');
            $auto_login_after_reg = isset($db_settings_data['set_auto_login_after_reg']) ? $db_settings_data['set_auto_login_after_reg'] : 'off';
        }

        $tabbed_css = isset($instance['tabbed_css']) ? $instance['tabbed_css'] : <<<CSS
.pp-tab-status {
    background: rgba(247, 245, 231, 0.7);
    padding: 10px 8px;
    margin: 0;
    color: #141412;
    border-radius: 5px;
    max-width: 300px;
}

.pp-tab-status a {
    color: #bc360a !important;
}

.flat-form {
    background: #edeff1;
    padding-bottom: 20px;
    margin: 10px auto;
    width: 100%;
    max-width: 300px;
    position: relative;
    font-family: Roboto, sans-serif;
}

li.remember-login {
    margin-bottom: 10px;
}

.pp-tab-widget {
    color: #fff !important;
    background: #2f4154;
    height: 40px;
    margin: 0;
    padding: 0;
    list-style-type: none;
    width: 100%;
    position: relative;
    display: block;
    margin-bottom: 20px;
}

.pp-tab-widget li {
    color: #fff !important;
    width: 30%;
    display: block;
    float: left;
    margin: 0;
    padding: 0;
}

.flat-form ul li:before {
    content: none  !important;
}

.pp-tab-widget a {
    color: #fff !important;
    background: #2f4154;
    display: block;
    float: left;
    text-decoration: none;
    font-size: 16px;
    padding: 5px 6px;

}

.pp-tab-widget li:last-child a {
    border-right: none;
    width: 90%;
    padding-left: 0;
    padding-right: 0;
    text-align: center;
}

ul.tab-widget {
    margin-left: 0 !important;
}

ul.tab-widget {
   margin-left: 0 !important;
}


.pp-tab-widget a.active {
    border-top: 4px solid #1abc9c;
    border-right: none;
    -webkit-transition: all 0.5s linear;
    -moz-transition: all 0.5s linear;
    transition: all 0.5s linear;
}

.pp-tab-widget a.focus {
    color: #2f4154 !important;
    outline: none !important;
}

.form-action {
    padding: 0 20px;
    position: relative;
}

.form-action h1 {
    font-size: 22px;
    font-weight: 500;
    margin: 0;
    padding-bottom: 10px;
}

.form-action .heading {
    font-size: 22px;
    font-weight: 500;
    margin: 0;
    padding-bottom: 10px;
}

.form-action p {
    font-size: 12px;
    padding-bottom: 10px;
    line-height: 25px;
}

.tab-widget input[type=text],
.tab-widget input[type=email],
.tab-widget input[type=password] {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    width: 100%;
    height: 40px;
    margin-bottom: 10px;
    padding-left: 15px;
    background: #fff;
    border: none;
    color: #e74c3c;
    outline: none;
}

.show {
    display: block;
}

.hide {
    display: none;
}

.tb-button {
    border: none;
    display: block;
    background: #136899;
    height: 40px;
    width: 80px;
    color: #ffffff;
    text-align: center;
    border-radius: 5px;
    /*box-shadow: 0px 3px 1px #2075aa;*/
    -webkit-transition: all 0.15s linear;
    -moz-transition: all 0.15s linear;
    transition: all 0.15s linear;
}

.tb-button:hover {
    background: #1e75aa;
}

.tb-button:active {
    background: #136899;
}
CSS;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>


        <p>
            <label for="<?php echo $this->get_field_id('login_text'); ?>"><?php _e('Login text:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('login_text'); ?>" name="<?php echo $this->get_field_name('login_text'); ?>" type="text" value="<?php echo esc_attr($login_text); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('reg_text'); ?>"><?php _e('Registration text:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('reg_text'); ?>" name="<?php echo $this->get_field_name('reg_text'); ?>" type="text" value="<?php echo esc_attr($reg_text); ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('lostp_text'); ?>"><?php _e('Lost-password text:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('lostp_text'); ?>" name="<?php echo $this->get_field_name('lostp_text'); ?>" type="text" value="<?php echo esc_attr($lostp_text); ?>">
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('auto_login_after_reg'); ?>" name="<?php echo $this->get_field_name('auto_login_after_reg'); ?>" type="checkbox" value="on" <?php checked($auto_login_after_reg, 'on'); ?>>
            <label for="<?php echo $this->get_field_id('auto_login_after_reg'); ?>"><?php _e('Automatically login user after successful registration'); ?></label>

        </p>


        <p>
            <label
                for="<?php echo $this->get_field_id('tabbed_css'); ?>"><?php _e('Widget CSS:'); ?></label>
            <textarea name="<?php echo $this->get_field_name('tabbed_css'); ?>" id="<?php echo $this->get_field_id('tabbed_css'); ?>" cols="20" rows="16" class="widefat"><?php echo esc_textarea($tabbed_css); ?></textarea>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance                         = array();
        $instance['title']                = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['login_text']           = ( ! empty($new_instance['login_text'])) ? strip_tags($new_instance['login_text']) : '';
        $instance['reg_text']             = ( ! empty($new_instance['reg_text'])) ? strip_tags($new_instance['reg_text']) : '';
        $instance['lostp_text']           = ( ! empty($new_instance['lostp_text'])) ? strip_tags($new_instance['lostp_text']) : '';
        $instance['auto_login_after_reg'] = ( ! empty($new_instance['auto_login_after_reg'])) ? strip_tags($new_instance['auto_login_after_reg']) : '';
        $instance['tabbed_css']           = ( ! empty($new_instance['tabbed_css'])) ? strip_tags($new_instance['tabbed_css']) : '';

        return $instance;
    }

} // class Foo_Widget

// register Foo_Widget widget
function register_tabbed_reg_widget()
{
    register_widget('Tabbed_login_reg_passkey');
}

add_action('widgets_init', 'register_tabbed_reg_widget');