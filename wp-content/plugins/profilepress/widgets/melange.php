<?php

/**
 * ProfilePress melange form as a widget
 */
class PP_Melange_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pp_melange_widget', // Base ID
            __('ProfilePress Melange Widget', 'profilepress'), // Name
            array('description' => __('ProfilePress melange forms available as widgets', 'profilepress'),)
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
        $melange_id  = absint($instance['chosen_melange']);
        $hide_widget = $instance['hide'];


        if ('yes' == $hide_widget && is_user_logged_in()) {
            return;
        }

        echo $args['before_widget'];

        if ( ! empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Melange structure
        $structure = PROFILEPRESS_sql::get_a_builder_structure('melange', $melange_id);

        //Melange CSS
        $css = PROFILEPRESS_sql::get_a_builder_css('melange', $melange_id);

        $login_response          = ProfilePress_Login_Auth::credentials_validation();
        $registration_response   = ProfilePress_Registration_Auth::validate_registration_form($melange_id, '', true);
        $password_reset_response = ProfilePress_Password_Reset::validate_password_reset_form($melange_id, true);
        $edit_profile_response   = ProfilePress_Edit_Profile::validate_form($melange_id, true);

        $response = '';
        if ( ! empty($login_response)) {
            $response = $login_response;
        } elseif ( ! empty($registration_response)) {
            $response = $registration_response;
        } elseif ( ! empty($password_reset_response)) {
            $response = $password_reset_response;
        } elseif ( ! empty($edit_profile_response)) {
            $response = $edit_profile_response;
        }

        echo "<style type=\"text/css\">$css</style>";
        if ( ! empty($response)) {
            echo $response;
        }

        echo do_shortcode($structure);

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance
     *
     * @return void
     */
    public function form($instance)
    {
        $title          = ! empty($instance['title']) ? $instance['title'] : '';
        $hide           = ! empty($instance['hide']) ? $instance['hide'] : '';
        $chosen_melange = ! empty($instance['chosen_melange']) ? $instance['chosen_melange'] : '';

        // get the list of melange builder available as a widget (array)
        $melange_ids = PROFILEPRESS_sql::get_a_builder_ids('melange');

        // array that will save the list of melange  available as widget
        $melanges_available_as_widget = array();

        // loop over the melange_builder and pick out the ones available as a widget
        foreach ($melange_ids as $id) {
            if ( ! is_null(PROFILEPRESS_sql::check_if_builder_is_widget($id, 'melange'))) {
                $melanges_available_as_widget[] = $id;
            }
        }

        // if no builder is made widget, return a message to that effect
        if (empty($melanges_available_as_widget)) {
            echo '<p>' . __(apply_filters('pp_no_melange_widget', 'No melange form is available as a widget'), 'profilepress') . '</p>';
        } else {
            ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('chosen_melange'); ?>"><?php _e('Select Melange', 'profilepress'); ?></label><br/>
                <select id="<?php echo $this->get_field_id('chosen_melange'); ?>" name="<?php echo $this->get_field_name('chosen_melange'); ?>" style="width:100%">
                    <?php
                    echo $chosen_melange;
                    foreach ($melanges_available_as_widget as $melange_id) {
                        echo "<option value='$melange_id'" . selected($melange_id, $chosen_melange, false) . '>' . PROFILEPRESS_sql::get_a_builder_title('melange', $melange_id) . "</option>";
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('hide'); ?>"><?php _e('Hide when a user is logged in:', 'profilepress'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('hide'); ?>" name="<?php echo $this->get_field_name('hide'); ?>" type="checkbox" value="yes" <?php checked($hide, 'yes'); ?>>
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
        $instance                   = array();
        $instance['title']          = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['hide']           = ( ! empty($new_instance['hide'])) ? strip_tags($new_instance['hide']) : '';
        $instance['chosen_melange'] = ( ! empty($new_instance['chosen_melange'])) ? absint($new_instance['chosen_melange']) : '';

        return $instance;
    }

} // class Foo_Widget


// register widget
function register_pp_melange_widget()
{
    register_widget('PP_Melange_Widget');
}

add_action('widgets_init', 'register_pp_melange_widget');