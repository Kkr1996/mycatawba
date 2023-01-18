(function ($) {
    $(document).ready(function () {
        $('.ppview .handlediv').click(function () {
            $(this).parent().toggleClass("closed").addClass('postbox');
        });

        // add IDs to table "tr" tags in profile fields wp-list-table
        // to be used by jQuery sortable.
        $('table.custom_profile_fields tbody tr').each(function (index) {
            $(this).attr('id', ++index);
        });

        // profile fields sortable
        $("table.custom_profile_fields tbody").sortable({
            cursor: "move",
            containment: "table",
            start: function (event, ui) {
                ui.item.toggleClass("alternate");
            },
            stop: function (event, ui) {
                ui.item.toggleClass("alternate");
            },
            update: function (event, ui) {
                var data = $(this).sortable('toArray');
                $.post(
                    ajaxurl, {
                        action: "pp_profile_fields_sortable",
                        data: data
                    }
                );

                // regenerate the table tr ids after each DOM update
                $('table.custom_profile_fields tbody tr').each(function (index) {
                    $(this).attr('id', ++index);
                });
            }
        });

        // profile fields sortable
        $("table#pp_contact_info tbody").sortable({
            cursor: "move",
            containment: "table",
            start: function (event, ui) {
                ui.item.toggleClass("alternate");
            },
            stop: function (event, ui) {
                ui.item.toggleClass("alternate");
            },
            update: function (event, ui) {
                var data = $(this).sortable('toArray');
                console.log(data);
                $.post(
                    ajaxurl, {
                        action: "pp_contact_info_sortable",
                        data: data
                    }
                );
            }
        });
    });
})(jQuery);