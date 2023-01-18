<?php

/**
 * @package ProfilePress custom file upload PHP class
 *
 * @since 1.10
 */
class PP_File_Uploader
{
    /** init poop */
    public static function init()
    {
        $result = array();

        if (empty($_FILES)) return $result;

        // remove registration and edit profile avatar from files uploaded to be processed.
        unset($_FILES['reg_avatar']);
        unset($_FILES['eup_avatar']);
        foreach ($_FILES as $field_key => $uploaded_file_array) {
            if (!empty($uploaded_file_array) && ($field_key != 'reg_avatar' || $field_key != 'eup_avatar')) {
                $result[$field_key] = self::process($uploaded_file_array, $field_key);
            }
        }

        return $result;
    }

    /**
     * Upload the file
     *
     * @param $file
     *
     * @return bool|WP_Error
     */
    public static function process($file, $field_key)
    {
        /**
         * Fires before image is process for validation and uploading
         */
        do_action('pp_before_saving_file', $file, $field_key);

        // validate all uploaded file but only return error for file with hidden "required-"field-key" field POSTed data.
        // i.e if the file has the "required" shortcode attribute.
        // added in "reg_custom_profile_field" in registration shortcode parser.

        if ($file["error"] !== 0) {
            self::error_file_logger($file, self::codeToMessage($file["error"]));
            if (isset($_POST["required-{$field_key}"])) {
                return new WP_Error(
                    'file_error',
                    apply_filters('pp_file_unexpected_error',
                        __('Unexpected error with file upload, Please try again.', 'profilepress'),
                        $field_key
                    )
                );
            } else {
                return;
            }
        }

        // filesize in megabyte. default is 10MB
        $file_size = apply_filters('pp_file_upload_size', 10, $field_key);

        if ($file["size"] > ($file_size * 1000000)) {
            return new WP_Error('file_too_large', apply_filters(
                    'pp_file_too_large',
                    sprintf(
                        __('Uploaded file is greater than the allowed sized of %s', 'profilepress'),
                        "$file_size MB"
                    ),
                    $field_key
                )
            );
        }

        // array of allowed file extensions
        $extensions = PROFILEPRESS_sql::get_field_option_values($field_key);
        $allowed_extensions = array_map('trim', explode(',', $extensions[0]));
        $filename = $file['name'];

        // get the file extension
        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowed_extensions)) {
            return new WP_Error('invalid_file', $filename . ' ' . apply_filters('pp_invalid_file_error', __('appears to be of an invalid file format. Please try again.', 'profilepress'), $field_key));
        }

        $file_upload_dir = apply_filters('pp_file_upload_dir', PP_FILE_UPLOAD_DIR, $field_key);

        // ensure a safe filename
        $file_name = preg_replace("/[^A-Z0-9._-]/i", "_", $filename);

        // don't overwrite an existing file
        $i = 0;
        $file_exist_parts = pathinfo($file_name);
        while (file_exists($file_upload_dir . $file_name)) {
            $i++;
            $file_name = $file_exist_parts["filename"] . "-" . $i . "." . $file_exist_parts["extension"];
        }

        // does file uploads folder exist? if NO, create it.
        if (!file_exists($file_upload_dir)) {
            mkdir($file_upload_dir, 0755);
        }

        // create index.php file in file uploads folder
        if (!file_exists($file_upload_dir . '/index.php')) {
            pp_create_index_file($file_upload_dir);
        }

        // preserve file from temporary directory
        $success = move_uploaded_file($file["tmp_name"], $file_upload_dir . $file_name);

        if (!$success) {
            return new WP_Error ('save_error',
                sprintf(__("Unable to save %s, please try again.", 'profilepress'), $file_name));
        } else {

            // set proper permissions on the new file
            chmod($file_upload_dir . $file_name, 0644);

            /**
             * Fires after file have been saved
             *
             * @param string $file_name uploaded image url
             */
            do_action('pp_after_saving_file', $file_name, $file_upload_dir);

            return $file_name;
        }
    }

    /**
     * Error message of file upload converted from errorCode to readable message.
     *
     * @param int $code
     *
     * @return string
     */
    public static function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = __("The uploaded file exceeds the upload_max_filesize directive in php.ini", 'profilepress');
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = __("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form", 'profilepress');
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = __("The uploaded file was only partially uploaded", 'profilepress');
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = __("No file was uploaded", 'profilepress');
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = __("Missing a temporary folder", 'profilepress');
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = __("Failed to write file to disk", 'profilepress');
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = __("File upload stopped by extension", 'profilepress');
                break;

            default:
                $message = "Unknown upload error";
                break;
        }

        return $message;
    }


    /**
     * @param array $file_array global $_File['field_key'] of the file.
     *
     * @param string $error_message
     */
    public static function error_file_logger($file_array, $error_message, $file = 'file_upload_bugs')
    {
        // does bugs folder exist? if NO, create it.
        if (!file_exists(PP_BUGS)) {
            mkdir(PP_BUGS, 0755);
        }
        // create index.php file in bugs folder
        if (!file_exists(PP_BUGS . '/index.php')) {
            pp_create_index_file(PP_BUGS);
        }

        $error = date('Y-m-d h:i:s', time()) . ': ' . $error_message . ' => ' . json_encode($file_array) . "\r\n";
        $handle = fopen(PP_BUGS . "$file.txt", 'ab');
        fwrite($handle, $error);
        fclose($handle);
    }
}