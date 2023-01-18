<?php require_once VIEWS . '/include.settings-page-tab.php'; ?>
<br/>
<a class="button-secondary" href="?page=pp-contact-info" title="Back to Field List">Back to Field List</a>
<div id="poststuff" class="ppview">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
                <div class="postbox">
                    <h3 class="hndle ui-sortable-handle">
                        <span><?php _e('Add Contact Information', 'profilepress'); ?></span></h3>
                    <form method="post">
                        <div class="inside">
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="label_name">Field Label*</label></th>
                                    <td>
                                        <input type="text" id="label_name" name="ci_label_name" class="regular-text code" value="<?php echo isset($_POST['ci_label_name']) ? esc_attr($_POST['ci_label_name']) : ''; ?>" required="required"/>
                                        <p class="description">Human readable name for display to users.</p>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row"><label for="label_name">Field Key</label></th>
                                    <td>
                                        <input type="text" id="label_name" name="ci_key" class="regular-text code" value="<?php echo isset($_POST['ci_key']) ? esc_attr($_POST['ci_key']) : ''; ?>" required="required"/>

                                        <p class="description">
                                            Machine readable name to represent this field in the Database.</p>
                                    </td>
                                </tr>

                            </table>
                            <p>
                                <?php wp_nonce_field('add_contact_info'); ?>
                                <input class="button-primary" type="submit" name="add_contact_info" value="Add Contact">
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