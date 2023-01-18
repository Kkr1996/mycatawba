<?php

if ( ! class_exists('MO_Feature_Plugin')) {
    class MO_Feature_Plugin
    {
        public static function init()
        {
            if (class_exists('MailOptin\Libsodium\Libsodium')) return;

            add_filter('install_plugins_table_api_args_featured', [__CLASS__, 'featured_plugins_tab']);
        }

        public static function featured_plugins_tab($args)
        {
            add_filter('plugins_api_result', [__CLASS__, 'inject_plugin'], 10, 3);

            return $args;
        }

        public static function inject_plugin($res, $action, $args)
        {
            //remove filter to avoid infinite loop.
            remove_filter('plugins_api_result', [__CLASS__, 'inject_plugin'], 10, 3);

            $api = plugins_api('plugin_information', array(
                'slug'   => 'mailoptin',
                'is_ssl' => is_ssl(),
                'fields' => array(
                    'banners'           => true,
                    'reviews'           => true,
                    'downloaded'        => true,
                    'active_installs'   => true,
                    'icons'             => true,
                    'short_description' => true,
                )
            ));

            if ( ! is_wp_error($api)) {
                array_unshift($res->plugins, $api);
            }

            return $res;
        }

        public static function instance()
        {
            add_action('plugins_loaded', [__CLASS__, 'init']);
        }
    }

    MO_Feature_Plugin::instance();
}