(function ($, window, undefined) {
    $(document).ready(function () {
        $('#pp-del-avatar').click(function (e) {

            // button text
            var button_text = $(this).text();
            // object of this(button clicked)
            var this_obj = $(this);

            e.preventDefault();
            swal({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover it.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    closeOnConfirm: false
                },
                function () {
                    swal.disableButtons();

                    $(this_obj).text('Deleting...');
                    $.post(pp_del_avatar_obj.ajaxurl, {
                        action: 'pp_del_avatar',
                        nonce: pp_del_avatar_obj.nonce
                    }).done(function (data) {
                        if ('error' in data && data.error == 'nonce_failed') {
                            $(this_obj).text(button_text);
                            swal('An error occurred, please try again');
                        }
                        else if ('success' in data) {
                            $("img[data-del='avatar']").attr('src', data.default);
                            $(this_obj).remove();
                            swal(
                                'Deleted!',
                                'Profile picture has been deleted.',
                                'success'
                            );
                        }
                    });

                });
        });


        // only enable if pp_disable_ajax_form filter is false.
        if (pp_ajax_form.disable_ajax_form === 'false') {

            function _remove_spinner(obj, is_tab_widget) {
                if (is_tab_widget) {
                    obj.parents('.flat-form').unwrap();
                    obj.parents('.flat-form').next('.pp-spinner').remove();
                }
                else {
                    // remove spinner
                    obj.unwrap();
                    obj.next('.pp-spinner').remove();
                }
            }

            function _add_spinner(obj, is_tab_widget) {
                if (is_tab_widget) {
                    // if this is tab widget, add the following spinner shebang on the wrapper/container div with class flatform.
                    obj.parents('.flat-form').wrapAll('<div id="pp-ajax-spinner" style="position: relative"></div>');
                    obj.parents('.flat-form').after('<div class="pp-spinner"></div>');
                }
                else {
                    obj.wrapAll('<div id="pp-ajax-spinner" style="position: relative"></div>');
                    obj.after('<div class="pp-spinner"></div>');
                }
            }

            // Ajax login
            $(document).on('submit', 'form[data-pp-form-submit="login"]', function (e) {
                e.preventDefault();

                //@todo client side validation of username/password to ensure it isnt empty

                var $login_form = $(this);

                var ajaxData = {
                    action: 'pp_ajax_login',
                    data: $(this).serialize()
                };

                // return true if this is a tab widget
                var is_tab_widget = $login_form.find('input[name="is-pp-tab-widget"]').val() == 'true';

                // remove any login error.
                $login_form.prev('.profilepress-login-status').remove();
                // remove tab widget status notice/message.
                $login_form.parents('.flat-form').prev('.pp-tab-status').remove();

                _add_spinner($login_form, is_tab_widget);

                $.post(pp_ajax_form.ajaxurl, ajaxData, function (response) {
                    // remove the spinner and do nothing if 0 is returned which perhaps means the user is
                    // already logged in.
                    // we are checking for null because response can be null hence we want the spinner removed.
                    if (response === null || typeof response !== 'object') {
                        _remove_spinner($login_form, is_tab_widget);
                        return;
                    }

                    if ('success' in response && response.success === true && 'redirect' in response) {
                        $login_form.triggerHandler('pp_login_form_success');

                        window.location.assign(response.redirect)
                    }
                    else {

                        $login_form.triggerHandler('pp_login_form_error');

                        if (is_tab_widget) {
                            // tab widget has its own class for status notice/message which is pp-tab-status thus the replacement.
                            var notice = response.message.replace('profilepress-login-status', 'pp-tab-status');
                            $login_form.parents('.flat-form').before(notice);
                        }
                        else {
                            $login_form.before(response.message);
                        }
                    }

                    _remove_spinner($login_form, is_tab_widget);

                }, 'json');
            });

            // Ajax Registration
            if (window.FormData) {
                $(document).on('submit', 'form[data-pp-form-submit="signup"]', function (e) {
                    e.preventDefault();

                    var $signup_form = $(this);
                    var melange_id = $('input#pp_melange_id').val() === undefined ? '' : $('input#pp_melange_id').val();

                    var formData = new FormData(this);
                    formData.append("action", "pp_ajax_signup");
                    formData.append("melange_id", melange_id);


                    // return true if this is a tab widget
                    var is_tab_widget = $signup_form.find('input[name="is-pp-tab-widget"]').val() == 'true';

                    // remove any prior signup error.
                    $signup_form.prev('.profilepress-reg-status').remove();
                    // remove tab widget status notice/message.
                    $signup_form.parents('.flat-form').prev('.pp-tab-status').remove();

                    _add_spinner($signup_form, is_tab_widget);

                    $.post({
                        url: pp_ajax_form.ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
                            // remove the spinner and do nothing if 0 is returned which perhaps means the user is
                            // already logged in.
                            if (typeof response !== 'object') {
                                _remove_spinner($signup_form, is_tab_widget);
                                return;
                            }

                            if ('message' in response) {
                                $signup_form.trigger('pp_registration_ajax_response', [response]);
                                if (is_tab_widget) {
                                    // tab widget has its own class for status notice/message which is pp-tab-status thus the replacement.
                                    var notice = response.message.replace('profilepress-reg-status', 'pp-tab-status');
                                    $signup_form.parents('.flat-form').before(notice);
                                }
                                else {
                                    $signup_form.before(response.message);
                                }
                            }
                            else if ('redirect' in response) {
                                $signup_form.trigger('pp_registration_success', [response]);
                                window.location.assign(response.redirect)
                            }
                            _remove_spinner($signup_form, is_tab_widget);
                        }
                    });
                });
            }

            // Ajax password reset
            $(document).on('submit', 'form[data-pp-form-submit="passwordreset"]', function (e) {
                e.preventDefault();

                var $passwordreset_form = $(this);
                var melange_id = $('input#pp_melange_id').val() === undefined ? '' : $('input#pp_melange_id').val();


                var ajaxData = {
                    action: 'pp_ajax_passwordreset',
                    // if this is melange, we need it ID thus "&melange_id". 
                    data: $(this).serialize() + '&melange_id=' + melange_id
                };

                // return true if this is a tab widget
                var is_tab_widget = $passwordreset_form.find('input[name="is-pp-tab-widget"]').val() == 'true';

                // remove any password reset error.
                $passwordreset_form.prev('.profilepress-reset-status').remove();
                $passwordreset_form.prev('.pp-reset-success').remove();
                // remove tab widget status notice/message.
                $passwordreset_form.parents('.flat-form').prev('.pp-tab-status').remove();

                _add_spinner($passwordreset_form, is_tab_widget);

                $.post(pp_ajax_form.ajaxurl, ajaxData, function (response) {
                    // remove the spinner and do nothing if 0 is returned which perhaps means the user is
                    // already logged in.
                    if (typeof response !== 'object') {
                        _remove_spinner($passwordreset_form, is_tab_widget);
                        return;
                    }

                    if ('message' in response) {
                        $passwordreset_form.triggerHandler('pp_password_reset_status');
                        if (is_tab_widget) {
                            // tab widget has its own class for status notice/message which is pp-tab-status thus the replacement.
                            var notice = response.message.replace('profilepress-reset-status', 'pp-tab-status');
                            $passwordreset_form.parents('.flat-form').before(notice);
                        }
                        else {
                            $passwordreset_form.before(response.message);
                        }

                        // hide the form after successful action
                        if ('status' in response && response.status === true) {
                            $passwordreset_form.hide();
                        }

                        $('input[name="user_login"]').val('');
                    }
                    _remove_spinner($passwordreset_form, is_tab_widget);

                }, 'json');
            });


            // Ajax edit profile
            if (window.FormData) {
                $(document).on('submit', 'form[data-pp-form-submit="editprofile"]', function (e) {
                    e.preventDefault();

                    var $editprofile_form = $('form[data-pp-form-submit="editprofile"]');
                    var melange_id = $('input#pp_melange_id').val() === undefined ? '' : $('input#pp_melange_id').val();

                    var formData = new FormData(this);
                    formData.append("action", "pp_ajax_editprofile");
                    formData.append("nonce", pp_ajax_form.nonce);
                    formData.append("melange_id", melange_id);

                    // remove any prior edit profile error.
                    $('.profilepress-edit-profile-status').remove();
                    $('.profilepress-edit-profile-success').remove();

                    // remove any prior status message. Fixes removal of message with custom class.
                    if ("" != window.edit_profile_msg_class) {
                        $('.' + window.edit_profile_msg_class).remove();
                    }

                    _add_spinner($editprofile_form);

                    $.post({
                        url: pp_ajax_form.ajaxurl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
                            $editprofile_form.triggerHandler('pp_edit_profile_status');
                            if ("avatar_url" in response && response.avatar_url != '') {
                                $("img[data-del='avatar']").attr('src', response.avatar_url)
                            }
                            if ('message' in response) {
                                // save the response error message class for early removal in next request.
                                window.edit_profile_msg_class = $(response.message).attr('class');

                                $editprofile_form.before(response.message);
                            }

                            if ('redirect' in response) {
                                $editprofile_form.triggerHandler('pp_edit_profile_success');
                                window.location.assign(response.redirect)
                            }

                            _remove_spinner($editprofile_form);
                        }
                    }, 'json');
                });
            }
        }
    });


})(jQuery, window, undefined);

function pp_chosen_browser_is_supported() {
    if ("Microsoft Internet Explorer" === window.navigator.appName) {
        return document.documentMode >= 8;
    }
    return !(/iP(od|hone)/i.test(window.navigator.userAgent) || /IEMobile/i.test(window.navigator.userAgent) || /Windows Phone/i.test(window.navigator.userAgent) || /BlackBerry/i.test(window.navigator.userAgent) || /BB10/i.test(window.navigator.userAgent) || /Android.*Mobile/i.test(window.navigator.userAgent));
}