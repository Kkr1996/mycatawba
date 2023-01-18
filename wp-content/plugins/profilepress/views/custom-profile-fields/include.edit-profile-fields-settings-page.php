<?php

// @GET field id to edit.
$field_id = absint($_GET['field']);

// get the profile fields row for the id
$profile_fields = PROFILEPRESS_sql::sql_profile_field_row_id($field_id);
$field_key      = $profile_fields['field_key'];

// select multi option selectable.
$is_multi_selectable = get_option('pp_cpf_select_multi_selectable', array());
$is_multi_selectable = array_key_exists($field_key, $is_multi_selectable) ? 'yes' : '';

// checkbox multi option selectable.
$is_multi_checkbox = get_option('pp_cpf_checkbox_multi_selectable', array());
$is_multi_checkbox = in_array($field_key, array_keys($is_multi_checkbox)) ? 'yes' : '';

require_once VIEWS . '/include.settings-page-tab.php'; ?>
<br/>
<a class="button-secondary" href="?page=<?php echo PROFILE_FIELDS_SETTINGS_PAGE_SLUG; ?>" title="<?php _e('Back to Catalog', 'profilepress'); ?>"><?php _e('Back to Catalog', 'profilepress'); ?></a>

<div id="poststuff" class="ppview">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">

            <div class="meta-box-sortables ui-sortable">

                <div class="postbox">

                    <h3><span><?php _e('Edit Custom Profile Fields', 'profilepress'); ?></span></h3>

                    <form method="post">
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="label_name"><?php _e('Field Label*', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <input required type="text" id="label_name" name="cpf_label_name" class="regular-text code" value="<?php echo isset($_POST['cpf_label_name']) ? esc_attr($_POST['cpf_label_name']) : $profile_fields['label_name']; ?>"/>
                                        <p class="description"><?php _e('Human readable name for display to users.', 'profilepress'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="key"><?php _e('Field Key*', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <input required type="text" id="key" name="cpf_key" class="regular-text code" value="<?php echo isset($_POST['cpf_key']) ? esc_attr($_POST['cpf_key']) : $field_key; ?>" pattern="[a-z0-9_]+"/>

                                        <p class="description">Machine readable name to represent this field in the
                                            Database.<br/> Character must be in
                                            <strong>lower-case</strong> and underscore
                                            <strong>"_"</strong> is the only supported special character.</p>
                                    </td>
                                </tr>


                                <tr>
                                    <th scope="row">
                                        <label for="description"><?php _e('Field Description', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <textarea name="cpf_description" id="description"><?php echo isset($_POST['cpf_description']) ? esc_attr($_POST['cpf_description']) : $profile_fields['description']; ?></textarea>
                                        <p class="description"><?php _e('Description of the field for display to users', 'profilepress'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="cpf_type"><?php _e('Type', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <select required id="cpf_type" name="cpf_type">
                                            <option value="text" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'text') : selected($profile_fields['type'], 'text'); ?>>
                                                <?php _e('Text Field', 'profilepress'); ?>
                                            </option>
                                            <option value="password" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'password') : selected($profile_fields['type'], 'password'); ?>>
                                                <?php _e('Password Field', 'profilepress'); ?>
                                            </option>
                                            <option value="email" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'email') : selected($profile_fields['type'], 'email'); ?>>
                                                <?php _e('Email Field', 'profilepress'); ?>
                                            </option>
                                            <option value="tel" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'tel') : selected($profile_fields['type'], 'tel'); ?>>
                                                <?php _e('Telephone Number Field', 'profilepress'); ?>
                                            </option>
                                            <option value="hidden" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'hidden') : selected($profile_fields['type'], 'hidden'); ?>>
                                                <?php _e('Hidden Field', 'profilepress'); ?>
                                            </option>
                                            <option value="number" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'number') : selected($profile_fields['type'], 'number'); ?>>
                                                <?php _e('Number Field', 'profilepress'); ?>
                                            </option>
                                            <option value="date" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'date') : selected($profile_fields['type'], 'date'); ?>>
                                                <?php _e('Date Field', 'profilepress'); ?>
                                            </option>
                                            <option value="country" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'country') : selected($profile_fields['type'], 'country'); ?>>
                                                <?php _e('Country Field', 'profilepress'); ?>
                                            </option>
                                            <option value="textarea" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'textarea') : selected($profile_fields['type'], 'textarea'); ?>>
                                                Textarea
                                            </option>
                                            <option value="agreeable" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'agreeable') : selected($profile_fields['type'], 'agreeable'); ?>>
                                                <?php _e('Agreeable (Checkbox)', 'profilepress'); ?>
                                            </option>
                                            <option value="file" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'file') : selected($profile_fields['type'], 'file'); ?>><?php _e('File Uploader', 'profilepress'); ?></option>
                                            <option value="select" <?php isset($_POST['cpf_type']) ? selected($_POST['cpf_type'], 'select') : selected($profile_fields['type'], 'select'); ?>>
                                                Multiple Choice: Select Box
                                            </option>
                                            <option value="radio" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'radio') : selected($profile_fields['type'], 'radio'); ?>>
                                                Multiple Choice: Radio Buttons
                                            </option>
                                            <option value="checkbox" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'checkbox') : selected(esc_attr($profile_fields['type']), 'checkbox'); ?>>
                                                Multiple Choice: Check Box
                                            </option>
                                        </select>

                                        <p class="description"><?php _e('Select the form element you want to use.', 'profilepress'); ?></p>
                                        <p class="description"><?php _e('If "Agreeable" is selected, only "Field Key" and "Field Label" (the statement to show besides the checkbox) are required. Others are optional.', 'profilepress'); ?></p>

                                        <p style="display:none" id="cpf-multi-select">
                                            <label>
                                                <input type="checkbox" name="cpf_multi_select" value="yes" <?php isset($_POST['cpf_multi_select']) ? checked(esc_attr($_POST['cpf_multi_select']), 'yes') : checked($is_multi_selectable, 'yes'); ?>>
                                                <strong><?php _e('Check to make this select dropdown "multiple options selectable".'); ?></strong>
                                            </label></p>

                                        <p style="display:none" id="cpf-multi-checkbox">
                                            <label>
                                                <input type="checkbox" name="cpf_multi_checkbox" value="yes" <?php isset($_POST['cpf_multi_checkbox']) ? checked(esc_attr($_POST['cpf_multi_checkbox']), 'yes') : checked($is_multi_checkbox, 'yes'); ?>>
                                                <strong><?php _e('Check to make this checkbox accept multiple checked values.', 'profilepress'); ?></strong>
                                            </label></p>
                                    </td>

                                <tr id="pp-custom-field-options-row">
                                    <th scope="row">Options</th>
                                    <td>
                                        <input type="text" name="cpf_options" class="regular-text code" value="<?php echo isset($_POST['cpf_options']) ? esc_attr($_POST['cpf_options']) : $profile_fields['options']; ?>"/>

                                        <p class="description">
                                            Only for use by "File uploader" and Multiple Choices field types.
                                            <br/>Separate multiple options with a comma.</p>

                                        <p>Say you want to add a radio button with options "yes" and "no"; Select radio
                                            buttons in
                                            <strong>Type</strong> add the options to the
                                            <strong>Options</strong> field separated with a comma ( i.e. yes,no ) </p>

                                        <p>For
                                            <strong>file uploader</strong>, specify the file extension the uploader
                                            should accept separated by comma(,). E.g. the following is the file
                                            extension for pictures / images:
                                            <code>png, jpg, gif</code>
                                        </p>
                                    </td>
                                </tr>
                                <?php do_action('pp_edit_profile_field_settings', $field_id); ?>
                            </table>
                            <p>
                                <?php wp_nonce_field('edit_custom_profile_fields'); ?>
                                <input class="button-primary" type="submit" name="edit_field" value="Edit Field">
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php include_once VIEWS . '/include.plugin-settings-sidebar.php'; ?>
    </div>
    <br class="clear">
</div>

<script type="text/javascript">
    jQuery(function ($) {
        var cpf_type = $('#cpf_type');

        cpf_type.change(function () {
            var cache = $('#cpf-multi-select'),
                cache2 = $('#cpf-multi-checkbox'),
                cache3 = $('#pp-custom-field-options-row'),
                selected_value = this.value;
            cache.hide();
            if (selected_value === 'select') {
                cache.show();
            }

            cache2.hide();
            if (selected_value === 'checkbox') {
                cache2.show();
            }

            cache3.hide();
            // contextual display of options field
            if ($.inArray(selected_value, ['select', 'radio', 'checkbox', 'file']) !== -1) {
                cache3.show();
            }
        });

        cpf_type.change();

    });
</script>