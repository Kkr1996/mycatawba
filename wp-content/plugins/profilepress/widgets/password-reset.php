<?php

/**
 * ProfilePress password_reset form as a widget
 */
class PP_Password_Reset_Form_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pp_password_reset_widget', // Base ID
            __('ProfilePress Password Reset Widget', 'profilepress'), // Name
            array('description' => __('ProfilePress password reset forms available as widgets', 'profilepress'),)
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
        $password_reset_id = $instance['chosen_password_reset'];

        if (is_user_logged_in() || empty($password_reset_id)) {
            return;
        }

        echo $args['before_widget'];

        if ( ! empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Password_Reset structure
        $structure = PROFILEPRESS_sql::get_a_builder_structure('password_reset', $password_reset_id);

        //Password_Reset CSS
        $css = PROFILEPRESS_sql::get_a_builder_css('password_reset', $password_reset_id);


        echo "<style type=\"text/css\">$css</style>";
        $password_reset_error = ProfilePress_Password_Reset::validate_password_reset_form($password_reset_id);

        if ( ! empty($password_reset_error)) {
            echo $password_reset_error;
        }

        $form_tag         = '<form data-pp-form-submit="passwordreset" method="post" action="' . esc_url_raw($_SERVER['REQUEST_URI']) . '">';
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
     */
    public function form($instance)
    {
        $title                 = ! empty($instance['title']) ? $instance['title'] : '';
        $chosen_password_reset = ! empty($instance['chosen_password_reset']) ? $instance['chosen_password_reset'] : '';

        // get the list of password_reset builder available as a widget (array)
        $password_reset_ids = PROFILEPRESS_sql::get_a_builder_ids('password_reset');

        // array that will save the list of password_reset  available as widget
        $password_resets_available_as_widget = array();

        // loop over the password_reset_builder and pick out the ones available as a widget
        foreach ($password_reset_ids as $id) {
            if ( ! is_null(PROFILEPRESS_sql::check_if_builder_is_widget($id, 'password_reset'))) {
                $password_resets_available_as_widget[] = $id;
            }
        }

        // if no builder is made widget, stop execution via return.
        if (empty($password_resets_available_as_widget)) {
            echo '<p>' . __(apply_filters('pp_no_password_reset_widget', 'No password-reset form is available as a widget'), 'profilepress') . '</p>';
        } else {
            ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('chosen_password_reset'); ?>"><?php _e('Select password_reset form'); ?></label><br/>
                <select id="<?php echo $this->get_field_id('chosen_password_reset'); ?>" name="<?php echo $this->get_field_name('chosen_password_reset'); ?>" style="width:100%">
                    <?php
                    echo $chosen_password_reset;
                    foreach ($password_resets_available_as_widget as $password_reset_id) {
                        echo "<option value='$password_reset_id'" . selected($password_reset_id, $chosen_password_reset, false) . '>' . PROFILEPRESS_sql::get_a_builder_title('password_reset', $password_reset_id) . "</option>";
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
        $instance                          = array();
        $instance['title']                 = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['chosen_password_reset'] = ( ! empty($new_instance['chosen_password_reset'])) ? absint($new_instance['chosen_password_reset']) : '';

        return $instance;
    }

} // class Foo_Widget


// register Foo_Widget widget
function register_pp_password_reset_widget()
{
    register_widget('PP_Password_Reset_Form_Widget');
}

add_action('widgets_init', 'register_pp_password_reset_widget');