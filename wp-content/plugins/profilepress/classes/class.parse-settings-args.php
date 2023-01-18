<?php

/**
 * Class PP_Parse_Settings_Args
 */
class PP_Parse_Settings_Args
{

    public static $section_title;

    public static function init($args_arrays, $db_options)
    {

        // variable declaration
        $html = '';

        foreach ($args_arrays as $args) {

            if (!empty($args['section_title'])) {
                $html .= self::header($args['section_title']);
            }

            // remove section title from array to make the argument keys be arrays so it can work with foreach loop
            unset($args['section_title']);

            foreach ($args as $key => $value) {
                if ($args[$key]['type'] == 'text') {
                    $html .= self::text($db_options, $key, $args[$key]);
                }
                if ($args[$key]['type'] == 'number') {
                    $html .= self::number($db_options, $key, $args[$key]);
                }
                if ($args[$key]['type'] == 'textarea') {
                    $html .= self::textarea($db_options, $key, $args[$key]);
                }
                if ($args[$key]['type'] == 'select') {
                    $html .= self::select($db_options, $key, $args[$key]);
                }
                if ($args[$key]['type'] == 'checkbox') {
                    $html .= self::checkbox($db_options, $key, $args[$key]);
                }
                if ($args[$key]['type'] == 'hidden') {
                    $html .= self::hidden($db_options, $key, $args[$key]);
                }
            }

            $html .= self::footer();
        }

        echo $html;
    }


    /**
     * Renders the text field
     *
     * @param array $db_options addons DB options
     * @param string $key array key of class argument
     * @param array $args class args
     *
     * @return string
     */
    public static function text($db_options, $key, $args)
    {
        $key = esc_attr($key);
        $label = esc_attr($args['label']);
        $description = $args['description'];
        ob_start(); ?>
        <tr>
            <th scope="row"><label for="<?php echo $key; ?>"><?php echo $label; ?></label></th>
            <td>
                <?php do_action('pp_parse_settings_before_text_field', $key, $db_options, $args); ?>
                <input type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" class="regular-text" value="<?php echo !empty($db_options[$key]) ? $db_options[$key] : ''; ?>"/>

                <p class="description"><?php echo $description; ?></p>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    /**
     * Renders the number text field
     *
     * @param array $db_options addons DB options
     * @param string $key array key of class argument
     * @param array $args class args
     *
     * @return string
     */
    public static function number($db_options, $key, $args)
    {
        $key = esc_attr($key);
        $label = esc_attr($args['label']);
        $description = $args['description'];
        ob_start(); ?>
        <tr>
            <th scope="row"><label for="<?php echo $key; ?>"><?php echo $label; ?></label></th>
            <td>
                <input type="number" id="<?php echo $key; ?>" name="<?php echo $key; ?>" class="regular-text" value="<?php echo !empty($db_options[$key]) ? $db_options[$key] : ''; ?>"/>
                <p class="description"><?php echo $description; ?></p>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }

    /**
     * Renders the number text field
     *
     * @param array $db_options addons DB options
     * @param string $key array key of class argument
     * @param array $args class args
     *
     * @return string
     */
    public static function hidden($db_options, $key, $args)
    {
        $key = esc_attr($key);
        $label = esc_attr($args['label']);
        $description = $args['description'];
        ob_start(); ?>
        <tr>
            <th scope="row"><label for="<?php echo $key; ?>"><?php echo $label; ?></label></th>
            <td>
                <input type="hidden" id="<?php echo $key; ?>" name="<?php echo $key; ?>" class="regular-text" value="<?php echo !empty($db_options[$key]) ? $db_options[$key] : ''; ?>"/>
                <p class="description"><?php echo $description; ?></p>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }


    /**
     * Renders the textarea field
     *
     * @param array $db_options addons DB options
     * @param string $key array key of class argument
     * @param array $args class args
     *
     * @return string
     */
    public static function textarea($db_options, $key, $args)
    {
        $key = esc_attr($key);
        $label = esc_attr($args['label']);
        $description = $args['description'];
        $rows = !empty($args['rows']) ? $args['rows'] : 5;
        $cols = !empty($args['column']) ? $args['column'] : '';
        ob_start();
        ?>
        <tr>
            <th scope="row"><label for="<?php echo $key; ?>"><?php echo $label; ?></label></th>
            <td>
                <textarea rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>" name="<?php echo $key; ?>" id="<?php echo $key; ?>"><?php echo !empty($db_options[$key]) ? stripslashes($db_options[$key]) : ''; ?></textarea>

                <p class="description"><?php echo $description; ?></p>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }


    /**
     * Renders the select dropdown
     *
     * @param array $db_options addons DB options
     * @param string $key array key of class argument
     * @param array $args class args
     *
     * @return string
     */
    public static function select($db_options, $key, $args)
    {
        $key = esc_attr($key);
        $label = esc_attr($args['label']);
        $description = $args['description'];
        $options = $args['options'];
        ob_start() ?>
        <tr>
            <th scope="row"><label for="<?php echo $key; ?>"><?php echo $label; ?></label></th>
            <td>
                <select id="<?php echo $key; ?>" name="<?php echo $key; ?>">
                    <?php foreach ($options as $option_key => $option_value) : ?>
                        <option value="<?php echo $option_key; ?>" <?php !empty($db_options[$key]) ? selected($db_options[$key], $option_key) : '' ?>><?php echo esc_attr($option_value); ?></option>
                    <?php endforeach; ?>
                </select>

                <p class="description"><?php echo $description; ?></p>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }


    /**
     * Renders the checkbox field
     *
     * @param array $db_options addons DB options
     * @param string $key array key of class argument
     * @param array $args class args
     *
     * @return string
     */
    public static function checkbox($db_options, $key, $args)
    {
        $key = esc_attr($key);
        $label = esc_attr($args['label']);
        $description = $args['description'];
        $checkbox_label = !empty($args['checkbox_label']) ? esc_attr($args['checkbox_label']) : __('Activate', 'profilepress');
        $value = !empty($args['value']) ? esc_attr($args['value']) : 'true';
        ob_start();
        ?>
        <tr>
            <th scope="row"><label for="<?php echo $key; ?>"><?php echo $label; ?></label></th>
            <td>
                <strong><label for="<?php echo $key; ?>"><?php echo $checkbox_label; ?></label></strong>
                <input type="checkbox" id="<?php echo $key; ?>" name="<?php echo $key; ?>" value="<?php echo $value; ?>" <?php !empty($db_options[$key]) ? checked($db_options[$key], $value) : '' ?> />

                <p class="description"><?php echo $description; ?></p>
            </td>
        </tr>
        <?php
        return ob_get_clean();
    }


    /**
     * Section header
     *
     * @param string $section_title
     *
     * @return string
     */
    public static function header($section_title)
    {
        ob_start();
        ?>
        <div class="postbox">
        <button type="button" class="handlediv button-link" aria-expanded="true">
            <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
            <span class="toggle-indicator" aria-hidden="true"></span>
        </button>
        <h3 class="hndle ui-sortable-handle"><span><?php echo esc_attr($section_title); ?></span></h3>
        <div class="inside">
            <table class="form-table">
        <?php
        return ob_get_clean();
    }


    /**
     * Section footer.
     *
     * @return string
     */
    public static function footer()
    {
        return '</table>
		<p><input class="button-primary" type="submit" name="save_extras" value="Save Changes"></p>
	</div>
</div>';
    }
}