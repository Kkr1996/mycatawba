<?php

require_once VIEWS . '/include.settings-page-tab.php'; ?>
<br/>
<a class="button-secondary" href="?page=<?php echo PROFILE_FIELDS_SETTINGS_PAGE_SLUG; ?>" title="<?php _e('Back to Catalog', 'profilepress'); ?>"><?php _e('Back to Catalog', 'profilepress'); ?></a>

<div id="poststuff" class="ppview">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
                <form method="post">
                    <div class="postbox">
                        <button type="button" class="handlediv button-link" aria-expanded="true">
                            <span class="screen-reader-text"><?php _e('Toggle panel'); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span></button>
                        <h3 class="hndle ui-sortable-handle">
                            <span><?php _e('Add Custom Profile Fields', 'profilepress'); ?></span></h3>

                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="label_name"><?php _e('Field Label*', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <input required type="text" id="label_name" name="cpf_label_name" class="regular-text code" value="<?php echo isset($_POST['cpf_label_name']) ? esc_attr($_POST['cpf_label_name']) : ''; ?>"/>
                                        <p class="description"><?php _e('Human readable name for display to users.', 'profilepress'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">
                                        <label for="key"><?php _e('Field Key*', 'profilepress'); ?></label></label>
                                    </th>
                                    <td>
                                        <input required type="text" id="key" name="cpf_key" class="regular-text code" value="<?php echo isset($_POST['cpf_key']) ? esc_attr($_POST['cpf_key']) : ''; ?>" pattern="[a-z0-9_]+"/>

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
                                        <textarea name="cpf_description" id="description"><?php echo isset($_POST['cpf_description']) ? esc_attr($_POST['cpf_description']) : ''; ?></textarea>
                                        <p class="description"><?php _e('Description of the field for display to users', 'profilepress'); ?></p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="cpf_type"><?php _e('Type', 'profilepress'); ?></label>
                                    </th>
                                    <td>
                                        <select required id="cpf_type" name="cpf_type">
                                            <option value="text" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'text') : ''; ?>><?php _e('Text Field', 'profilepress'); ?></option>
                                            <option value="password" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'password') : ''; ?>><?php _e('Password Field', 'profilepress'); ?></option>
                                            <option value="email" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'email') : ''; ?>><?php _e('Email Field', 'profilepress'); ?></option>
                                            <option value="tel" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'tel') : ''; ?>><?php _e('Telephone Number Field', 'profilepress'); ?></option>
                                            <option value="hidden" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'hidden') : ''; ?>><?php _e('Hidden Field', 'profilepress'); ?></option>
                                            <option value="number" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'number') : ''; ?>><?php _e('Number Field', 'profilepress'); ?></option>
                                            <option value="date" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'date') : ''; ?>><?php _e('Date Field', 'profilepress'); ?></option>
                                            <option value="country" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'country') : ''; ?>><?php _e('Country Field', 'profilepress'); ?></option>
                                            <option value="textarea" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'textarea') : ''; ?>><?php _e('Textarea', 'profilepress'); ?></option>
                                            <option value="agreeable" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'agreeable') : ''; ?>><?php _e('Agreeable (Checkbox)', 'profilepress'); ?></option>
                                            <option value="file" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'file') : ''; ?>><?php _e('File Uploader', 'profilepress'); ?></option>
                                            <option value="select" <?php isset($_POST['cpf_type']) ? selected($_POST['cpf_type'], 'select') : ''; ?>><?php _e('Multiple Choice: Select Box', 'profilepress'); ?></option>
                                            <option value="radio" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'radio') : ''; ?>><?php _e('Multiple Choice: Radio Buttons', 'profilepress'); ?></option>
                                            <option value="checkbox" <?php isset($_POST['cpf_type']) ? selected(esc_attr($_POST['cpf_type']), 'checkbox') : ''; ?>><?php _e('Multiple Choice: Check Box', 'profilepress'); ?></option>
                                        </select>

                                        <p class="description"><?php _e('Select the form element you want to use.', 'profilepress'); ?></p>
                                        <p class="description"><?php _e('If "Agreeable" is selected, only "Field Key" and "Field Label" (the statement to show besides the checkbox) are required. Others are optional.', 'profilepress'); ?></p>

                                        <p style="display:none" id="cpf-multi-select">
                                            <label>
                                                <input type="checkbox" name="cpf_multi_select" value="yes" <?php isset($_POST['cpf_multi_select']) ? checked(esc_attr($_POST['cpf_multi_select']), 'yes') : ''; ?>>
                                                <strong><?php _e('Check to make this select dropdown "multiple options selectable".'); ?></strong>
                                            </label></p>

                                        <p style="display:none" id="cpf-multi-checkbox">
                                            <label>
                                                <input type="checkbox" name="cpf_multi_checkbox" value="yes" <?php isset($_POST['cpf_multi_checkbox']) ? checked(esc_attr($_POST['cpf_multi_checkbox']), 'yes') : ''; ?>>
                                                <strong><?php _e('Check to make this checkbox accept multiple checked values.', 'profilepress'); ?></strong>
                                            </label></p>
                                    </td>

                                <tr>
                                    <th scope="row">Options</th>
                                    <td>
                                        <input type="text" name="cpf_options" class="regular-text code" value="<?php echo isset($_POST['cpf_options']) ? esc_attr($_POST['cpf_options']) : ''; ?>"/>

                                        <p class="description">
                                            Only for use by File uploader and Multiple Choices (i.e
                                            <strong>Select Box</strong> & <strong>Radio Buttons</strong>)
                                            <br/> Separate multiple options with a comma.</p>

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

                                <?php do_action('pp_add_profile_field_settings') ?>

                            </table>
                            <p>
                                <?php wp_nonce_field('add_custom_profile_fields'); ?>
                                <?php if (apply_filters('pp_hide_add_custom_field_button', true)): ?>
                                    <input class="button-primary" type="submit" name="add_new_field" value="Add Field">
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php do_action('pp_after_add_profile_field_settings') ?>
                </form>
            </div>
        </div>
        <?php include_once VIEWS . '/include.plugin-settings-sidebar.php'; ?>
    </div>
    <br class="clear">
</div>

<script>
    jQuery(function ($) {
        if ($('#cpf_type').val() == 'select') {
            $('p#cpf-multi-select').show();
        }
        if ($('#cpf_type').val() == 'checkbox') {
            $('p#cpf-multi-checkbox').show();
        }

        $('#cpf_type').change(function (e) {
            if ($('#cpf_type').val() == 'select') {
                $('p#cpf-multi-select').show();
            }
            else {
                $('p#cpf-multi-select').hide();
            }

            if ($('#cpf_type').val() == 'checkbox') {
                $('p#cpf-multi-checkbox').show();
            }
            else {
                $('p#cpf-multi-checkbox').hide();
            }
        })
    })
</script>
