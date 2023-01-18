<?php

/**
 * ProfilePress edit_profile form as a widget
 */
class PP_Edit_Profile_Form_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        parent::__construct(
            'pp_edit_profile_widget', // Base ID
            __('ProfilePress Edit Profile Widget', 'profilepress'), // Name
            array('description' => __('ProfilePress edit-profile forms available as widgets', 'profilepress'),)
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
        // saved widget form ID
        $edit_profile_id = $instance['chosen_edit_profile'];

        if ( ! is_user_logged_in() || empty($edit_profile_id)) {
            return;
        }

        echo $args['before_widget'];

        if ( ! empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Edit_Profile structure
        $structure = PROFILEPRESS_sql::get_a_builder_structure('edit_user_profile', $edit_profile_id);

        //Edit_Profile CSS
        $css = PROFILEPRESS_sql::get_a_builder_css('edit_user_profile', $edit_profile_id);


        echo "<style type=\"text/css\">$css</style>";

        $edit_profile_error = ProfilePress_Edit_Profile::validate_form($edit_profile_id);

        if ( ! empty($edit_profile_error)) {
            echo $edit_profile_error;
        }

        $form_tag         = '<form method="post" action="' . esc_url_raw($_SERVER['REQUEST_URI']) . '" enctype="multipart/form-data">';
        $parsed_structure = do_shortcode($structure);
        echo "$form_tag $parsed_structure </form>";


        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance saved widget field data
     *
     * @return string|void
     */
    public function form($instance)
    {
        $title               = ! empty($instance['title']) ? $instance['title'] : '';
        $chosen_edit_profile = ! empty($instance['chosen_edit_profile']) ? $instance['chosen_edit_profile'] : '';

        // get the list of edit_profile builder available as a widget (array)
        $edit_profile_ids = PROFILEPRESS_sql::get_a_builder_ids('edit_user_profile');

        // array that will save the list of edit_profile  available as widget
        $edit_profiles_available_as_widget = array();

        // loop over the edit_profile_builder and pick out the ones available as a widget
        foreach ($edit_profile_ids as $id) {
            if ( ! is_null(PROFILEPRESS_sql::check_if_builder_is_widget($id, 'edit_user_profile'))) {
                $edit_profiles_available_as_widget[] = $id;
            }
        }

        // if no builder is made widget, stop execution via return.
        if (empty($edit_profiles_available_as_widget)) {
            echo '<p>' . __(apply_filters('pp_no_edit_profile_widget', 'No edit-profile form is available as a widget'), 'profilepress') . '</p>';
        } else {
            ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('chosen_edit_profile'); ?>"><?php _e('Select edit_profile form'); ?></label><br/>
                <select id="<?php echo $this->get_field_id('chosen_edit_profile'); ?>" name="<?php echo $this->get_field_name('chosen_edit_profile'); ?>" style="width:100%">
                    <?php
                    echo $chosen_edit_profile;
                    foreach ($edit_profiles_available_as_widget as $edit_profile_id) {
                        echo "<option value='$edit_profile_id'" . selected($edit_profile_id, $chosen_edit_profile, false) . '>' . PROFILEPRESS_sql::get_a_builder_title('edit_user_profile', $edit_profile_id) . "</option>";
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
        $instance['chosen_edit_profile'] = ( ! empty($new_instance['chosen_edit_profile'])) ? absint($new_instance['chosen_edit_profile']) : '';

        return $instance;
    }

} // class Foo_Widget


// register Foo_Widget widget
function register_pp_edit_profile_widget()
{
    register_widget('PP_Edit_Profile_Form_Widget');
}

add_action('widgets_init', 'register_pp_edit_profile_widget');