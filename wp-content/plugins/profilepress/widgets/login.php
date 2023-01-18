<?php

/**
 * ProfilePress login form as a widget
 */
class PP_Login_Form_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pp_login_widget', // Base ID
            __('ProfilePress Login Widget', 'profilepress'), // Name
            array('description' => __('ProfilePress login forms available as widgets', 'profilepress'),)
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        $login_id = $instance['chosen_login'];

        if (is_user_logged_in() || empty($login_id)) {
            return;
        }

        echo $args['before_widget'];

        if ( ! empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Login structure
        $structure = PROFILEPRESS_sql::get_a_builder_structure('login', $login_id);

        //Login CSS
        $css = PROFILEPRESS_sql::get_a_builder_css('login', $login_id);


        echo "<style type=\"text/css\">$css</style>";
        $login_error = ProfilePress_Login_Auth::credentials_validation($login_id);

        if ( ! empty($login_error)) {
            echo $login_error;
        }

        $redirect         = pp_login_redirect();
        $form_tag         = '<form data-pp-form-submit="login" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '">';
        $parsed_structure = do_shortcode($structure);
        echo "$form_tag $parsed_structure </form>";


        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     *
     * @return void
     */
    public function form($instance)
    {
        $title        = ! empty($instance['title']) ? $instance['title'] : '';
        $chosen_login = ! empty($instance['chosen_login']) ? $instance['chosen_login'] : '';

        // get the list of login builder available as a widget (array)
        $login_ids = PROFILEPRESS_sql::get_a_builder_ids('login');

        // array that will save the list of login  available as widget
        $logins_available_as_widget = array();

        // loop over the login_builder and pick out the ones available as a widget
        foreach ($login_ids as $id) {
            if ( ! is_null(PROFILEPRESS_sql::check_if_builder_is_widget($id, 'login'))) {
                $logins_available_as_widget[] = $id;
            }
        }

        // if no builder is made widget, stop execution via return.
        if (empty($logins_available_as_widget)) {
            echo '<p>' . __(apply_filters('pp_no_login_widget', 'No login form is available as a widget'), 'profilepress') . '</p>';
        } else {
            ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('chosen_login'); ?>"><?php _e('Select login form'); ?></label><br/>
                <select id="<?php echo $this->get_field_id('chosen_login'); ?>" name="<?php echo $this->get_field_name('chosen_login'); ?>" style="width:100%">
                    <?php
                    echo $chosen_login;
                    foreach ($logins_available_as_widget as $login_id) {
                        echo "<option value='$login_id'" . selected($login_id, $chosen_login, false) . '>' . PROFILEPRESS_sql::get_a_builder_title('login', $login_id) . "</option>";
                    }
                    ?>
                </select>
            </p>
            <?php
        }
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
        $instance                 = array();
        $instance['title']        = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['chosen_login'] = ( ! empty($new_instance['chosen_login'])) ? absint($new_instance['chosen_login']) : '';

        return $instance;
    }

} // class Foo_Widget


// register Foo_Widget widget
function register_pp_login_widget()
{
    register_widget('PP_Login_Form_Widget');
}

add_action('widgets_init', 'register_pp_login_widget');