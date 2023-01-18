<?php

class PP_GDPR
{
    public function __construct()
    {
        add_filter('wp_privacy_personal_data_exporters', [$this, 'wp_export_data']);
        add_filter('wp_privacy_personal_data_erasers', [$this, 'wp_erase_data']);
    }

    public function wp_erase_data($erasers)
    {
        $erasers['profilepress'] = [
            'eraser_friendly_name' => __('User Extra Information', 'mailoptin'),
            'callback' => [$this, 'erase_data']
        ];

        return $erasers;
    }

    public function erase_data($email_address)
    {
        $user = get_user_by('email', $email_address);
        $user_id = $user->ID;
        $custom_fields = PROFILEPRESS_sql::sql_wp_list_table_profile_fields();

        if (!empty($custom_fields) && is_array($custom_fields)) {

            foreach ($custom_fields as $custom_field) {

                $get_meta_value = get_user_meta($user_id, $custom_field['field_key']);
                if (empty($get_meta_value)) continue;

                $items_removed = false;
                $items_retained = false;

                $deleted = delete_user_meta($user_id, $custom_field['field_key']);
                if ($deleted) {
                    $items_removed = true;
                } else {
                    $items_retained = true;
                }
            }

            return [
                'items_removed' => $items_removed,
                'items_retained' => $items_retained,
                'messages' => [],
                'done' => true,
            ];
        }
    }

    public function wp_export_data($exporters)
    {
        $exporters[] = array(
            'exporter_friendly_name' => __('User Extra Information', 'mailoptin'),
            'callback' => function ($email_address) {
                $user = get_user_by('email', $email_address);
                $user_id = $user->ID;

                $data_to_export = [];
                $custom_fields = PROFILEPRESS_sql::sql_wp_list_table_profile_fields();

                if (!empty($custom_fields) && is_array($custom_fields)) {

                    $data_to_export = [];
                    foreach ($custom_fields as $custom_field) {
                        $usermeta_value = get_user_meta($user_id, $custom_field['field_key'], true);

                        if (!empty($usermeta_value))
                            $lead_data_to_export[] = [
                                'name' => $custom_field['label_name'],
                                'value' => $usermeta_value
                            ];
                    }

                    $data_to_export[] = [
                        'group_id' => 'profilepress',
                        'group_label' => __('User Extra Information', 'mailoptin'),
                        'item_id' => "profilepress-{$user_id}",
                        'data' => $lead_data_to_export
                    ];
                }

                return [
                    'data' => $data_to_export,
                    'done' => true,
                ];
            }
        );

        return $exporters;
    }
}


new PP_GDPR();