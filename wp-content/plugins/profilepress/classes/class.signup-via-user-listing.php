<?php

class PP_Signup_Location_User_Listing_Page
{

    public function __construct()
    {
        // add custom column to use listing
        add_filter('manage_users_columns', array($this, 'add_column'));
        add_action('manage_users_custom_column', array($this, 'populate_column'), 10, 3);
    }

    public function add_column($columns)
    {
        $column_name = apply_filters('pp_signup_via_column', __('Registered Via', 'pp_ec'));
        $columns['signup_via'] = $column_name;

        return $columns;

    }

    public function populate_column($status, $column_name, $user_id)
    {
        if ('signup_via' == $column_name) {

            $melange_val = get_user_meta($user_id, '_pp_signup_melange_via', true);
            $val = get_user_meta($user_id, '_pp_signup_via', true);

            if (!empty($melange_val)) {
                return PROFILEPRESS_sql::get_a_builder_title('melange', (int)$melange_val);
            }

            if (!empty($val)) {

                switch ($val) {
                    case 'facebook':
                        $status = __('Facebook', 'profilepress');
                        break;

                    case 'twitter':
                        $status = __('Twitter', 'profilepress');
                        break;

                    case 'google':
                        $status = __('Google', 'profilepress');
                        break;

                    case 'linkedin':
                        $status = __('LinkedIn', 'profilepress');
                        break;

                    case 'github':
                        $status = __('GitHub', 'profilepress');
                        break;

                    case 'vk':
                        $status = __('Vkontakte', 'profilepress');
                        break;

                    case 'tab_widget':
                        $status = __('Tabbed Widget', 'profilepress');
                        break;
                    default:
                        // value should be an integer
                        $status = PROFILEPRESS_sql::get_a_builder_title('registration', (int)$val);
                }
            }
        }


        return $status;
    }

    /**
     * Singleton poop
     *
     * @return PP_Passwordless_Login
     */
    public static function get_instance()
    {
        static $instance;

        if (!isset($instance)) {
            $instance = new self;
        }

        return $instance;
    }
}

PP_Signup_Location_User_Listing_Page::get_instance();