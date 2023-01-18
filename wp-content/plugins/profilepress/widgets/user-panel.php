<?php

/**
 * ProfilePress user panel widget,
 */
class PP_User_Panel_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'pp_user_panel_widget',
            __('ProfilePress - User Panel', 'profilepress'),
            array(
                'description' => __('Display currently logged in users avatar and links to logout and edit the profile.', 'profilepress'),
            ),
            array('width' => 400, 'height' => 350)// Args
        );
    }


    /**
     * Display Widget.
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {

        if (is_user_logged_in()) {

            $edit_profile_label = ! empty($instance['edit_profile_label']) ? sanitize_text_field($instance['edit_profile_label']) : __('Edit your Profile', 'profilepress');
            $logout_label       = ! empty($instance['logout_label']) ? sanitize_text_field($instance['logout_label']) : __('Log Out', 'profilepress');

            echo $args['before_widget'];

            $user_data = wp_get_current_user();
            ?>

            <div class="tile">
                <?php do_action('pp_before_user_panel_widget'); ?>

                <?php if (empty($instance['remove_avatar']) || $instance['remove_avatar'] != 'on') : ?>
                    <a href="<?php echo pp_profile_url(); ?>" title="<?php _e('Click to view your profile', 'profilepress') ?>">
                        <div class="demo-download">
                            <?php echo get_avatar($user_data->ID, 300); ?>
                        </div>
                    </a>
                <?php endif; ?>

                <h3 class="tile-title"><?php printf('Welcome %s', ucfirst($user_data->display_name)); ?></h3><br/>
                <p>
                    <a class="btn btn-inverse" href="<?php echo pp_edit_profile_url(); ?>"><?php echo $edit_profile_label; ?></a>
                </p>

                <p>
                    <a class="btn btn-inverse" href="<?php echo wp_logout_url(); ?>"><?php echo $logout_label; ?></a>
                </p>

                <?php do_action('pp_after_user_panel_widget'); ?>
            </div>

            <?php
        }
        echo $args['after_widget'];

    }


    public
    function form(
        $instance
    ) {
        $title              = ! empty($instance['title']) ? sanitize_text_field($instance['title']) : __('User Panel', 'profilepress');
        $remove_avatar      = ! empty($instance['remove_avatar']) ? sanitize_text_field($instance['remove_avatar']) : '';
        $edit_profile_label = ! empty($instance['edit_profile_label']) ? sanitize_text_field($instance['edit_profile_label']) : __('Edit your Profile', 'profilepress');
        $logout_label       = ! empty($instance['logout_label']) ? sanitize_text_field($instance['logout_label']) : __('Log Out', 'profilepress');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('remove_avatar'); ?>" name="<?php echo $this->get_field_name('remove_avatar'); ?>" type="checkbox" value="on" <?php checked($remove_avatar, 'on'); ?>>
            <label for="<?php echo $this->get_field_id('remove_avatar'); ?>"><?php _e('Check to remove user profile picture from panel.'); ?></label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('edit_profile_label'); ?>"><?php _e('Label for "edit profile" link:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('edit_profile_label'); ?>" name="<?php echo $this->get_field_name('edit_profile_label'); ?>" type="text" value="<?php echo $edit_profile_label; ?>">
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('logout_label'); ?>"><?php _e('Label for logout link:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('logout_label'); ?>" name="<?php echo $this->get_field_name('logout_label'); ?>" type="text" value="<?php echo $logout_label; ?>">
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
    public
    function update(
        $new_instance, $old_instance
    ) {
        $instance                       = array();
        $instance['title']              = ( ! empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['remove_avatar']      = ( ! empty($new_instance['remove_avatar'])) ? strip_tags($new_instance['remove_avatar']) : '';
        $instance['edit_profile_label'] = ( ! empty($new_instance['edit_profile_label'])) ? strip_tags($new_instance['edit_profile_label']) : '';
        $instance['logout_label']       = ( ! empty($new_instance['logout_label'])) ? strip_tags($new_instance['logout_label']) : '';

        return $instance;
    }

}

function pp_register_pp_user_panel_widget()
{
    register_widget('PP_User_Panel_Widget');
}

add_action('widgets_init', 'pp_register_pp_user_panel_widget');