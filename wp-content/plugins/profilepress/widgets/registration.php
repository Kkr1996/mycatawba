<?php

/**
 * ProfilePress registration form as a widget
 */
class PP_Registration_Form_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pp_registration_widget', // Base ID
            __('ProfilePress Registration Widget', 'profilepress'), // Name
            array('description' => __('ProfilePress registration forms available as widgets', 'profilepress'),)
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
        $registration_id = $instance['chosen_registration'];

        if (is_user_logged_in() || empty($registration_id)) {
            return;
        }

        echo $args['before_widget'];

        if ( ! empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Registration structure
        $structure = PROFILEPRESS_sql::get_a_builder_structure('registration', $registration_id);

        //Registration CSS
        $css = PROFILEPRESS_sql::get_a_builder_css('registration', $registration_id);


        echo "<style type=\"text/css\">$css</style>";
        $registration_error = ProfilePress_Registration_Auth::validate_registration_form($registration_id);

        if ( ! empty($registration_error)) {
            echo $registration_error;
        }

        $form_tag         = '<form data-pp-form-submit="signup" method="post" action="' . esc_url($_SERVER['REQUEST_URI']) . '">';
        $parsed_structure = do_shortcode($structure);
        echo "$form_tag $parsed_structure </form>";


        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance
     *
     * @return string
     */
    public function form($instance)
    {
        $title               = ! empty($instance['title']) ? $instance['title'] : '';
        $chosen_registration = ! empty($instance['chosen_registration']) ? $instance['chosen_registration'] : '';

        // get the list of registration builder available as a widget (array)
        $registration_ids = PROFILEPRESS_sql::get_a_builder_ids('registration');

        // array that will save the list of registration  available as widget
        $registrations_available_as_widget = array();

        // loop over the registration_builder and pick out the ones available as a widget
        foreach ($registration_ids as $id) {
            if ( ! is_null(PROFILEPRESS_sql::check_if_builder_is_widget($id, 'registration'))) {
                $registrations_available_as_widget[] = $id;
            }
        }

        // if no builder is made widget, stop execution via return.
        if (empty($registrations_available_as_widget)) {
            echo '<p>' . __(apply_filters('pp_no_registration_widget', 'No registration form is available as a widget'), 'profilepress') . '</p>';
        } else {
            ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('chosen_registration'); ?>"><?php _e('Select registration form'); ?></label><br/>
                <select id="<?php echo $this->get_field_id('chosen_registration'); ?>" name="<?php echo $this->get_field_name('chosen_registration'); ?>" style="width:100%">
                    <?php
                    echo $chosen_registration;
                    foreach ($registrations_available_as_widget as $registration_id) {
                        echo "<option value='$registration_id'" . selected($registration_id, $chosen_registration, false) . '>' . PROFILEPRESS_sql::get_a_builder_title('registration', $registration_id) . "</option>";
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
        $instance                        = array();
        $instance['title']               = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['chosen_registration'] = ( ! empty($new_instance['chosen_registration'])) ? absint($new_instance['chosen_registration']) : '';

        return $instance;
    }

} // class Foo_Widget


// register Foo_Widget widget
function register_pp_registration_widget()
{
    register_widget('PP_Registration_Form_Widget');
}

add_action('widgets_init', 'register_pp_registration_widget');