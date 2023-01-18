<?php

/**
 * @package ProfilePress user avatar upload PHP class
 * @since 1.0
 */
class PP_User_Avatar_Upload
{

    /**
     * Upload user profile avatar
     *
     * @param $avatar
     *
     * @return bool|WP_Error
     */
    public static function process($avatar)
    {
        /**
         * Fires before image is process for validation and uploading
         */
        do_action('pp_before_saving_avatar', $avatar);

        if (!empty($avatar)) {

            if ($avatar["error"] !== 0) {
                return new WP_Error(
                    'file_error',
                    apply_filters('pp_avatar_unexpected_error',
                        __('Unexpected error with image upload, Please try again.', 'profilepress')));
            }

            // filesize in kilobyte. default is 500KB
            $file_size = apply_filters('pp_avatar_upload_size', 500);

            if ($avatar["size"] > ($file_size * 1000)) {
                return new WP_Error('file_too_large', apply_filters(
                        'pp_avatar_too_large',
                        sprintf(
                            __('Uploaded avatar is greater than the allowed sized of %s', 'profilepress'),
                            "$file_size KB")
                    )
                );
            }

            // verify the file is a GIF, JPEG, or PNG
            $fileType = exif_imagetype($avatar["tmp_name"]);

            $allowed_avatar_type = apply_filters('pp_allowed_avatar_type', array(
                IMAGETYPE_GIF,
                IMAGETYPE_JPEG,
                IMAGETYPE_PNG
            ));

            if (!in_array($fileType, $allowed_avatar_type)) {
                return new WP_Error('image_invalid',
                    apply_filters('pp_avatar_not_image_error', __('Uploaded file not an image.', 'profilepress')));
            }

            $avatar_upload_dir = apply_filters('pp_avatar_upload_dir', AVATAR_UPLOAD_DIR);

            // ensure a safe filename
            $file_name = preg_replace("/[^A-Z0-9._-]/i", "_", $avatar["name"]);

            // explode the file
            $parts = pathinfo($file_name);

            $file_name = md5($parts["filename"]) . '.' . $parts["extension"];

            // don't overwrite an existing file
            $i = 0;
            $file_exist_parts = pathinfo($file_name);
            while (file_exists($avatar_upload_dir . $file_name)) {
                $i++;
                $file_name = $file_exist_parts["filename"] . "-" . $i . "." . $file_exist_parts["extension"];
                $file_name = md5($file_name) . '.' . $file_exist_parts["extension"];
            }

            if (!file_exists($avatar_upload_dir)) {
                mkdir($avatar_upload_dir, 0755);
            }

            // create index.php file in theme assets folder
            if (!file_exists($avatar_upload_dir . '/index.php')) {
                pp_create_index_file($avatar_upload_dir);
            }

            // preserve file from temporary directory
            $success = move_uploaded_file($avatar["tmp_name"], $avatar_upload_dir . $file_name);

            if (!$success) {
                return new WP_Error ('save_error', __('Unable to save file, please try again.', 'profilepress'));

            } else {

                // set proper permissions on the new file
                chmod($avatar_upload_dir . $file_name, 0644);

                /**
                 * Fires after avatar have been saved
                 *
                 * @param string $file_name uploaded image url
                 */
                do_action('pp_after_saving_avatar', $file_name, $avatar_upload_dir);

                return $file_name;
            }
        }
    }
}