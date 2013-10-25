// JavaScript Document
var dtech = {
    old_drop_val: "",
    custom_alert: function(output_msg, title_msg)
    {
        jQuery(".ui-widget ui-widget-content").remove();
        if (!title_msg)
            title_msg = 'Alert';
        if (!output_msg)
            output_msg = 'No Message to Display.';
        jQuery("<div id='custom_dialoge'></div>").html(output_msg).dialog({
            title: title_msg,
            resizable: false,
            modal: true,
            open: function(event, ui) {
                setTimeout(function() {
                    jQuery(".ui-button").trigger("click");
                }, 3000);
            },
            buttons: {
                "Ok": function()
                {
                    jQuery(this).dialog("close");
                }
            }
        });
    },
    /*
     * to update element on ajax all
     * @param {type} ajax_url
     * @param {type} update_element_id
     * @param {type} resource_elem_id
     * @returns {undefined}
     */
    updateElementAjax: function(ajax_url, update_element_id, resource_elem_id) {

        if (jQuery("#" + resource_elem_id).val() != "") {
            jQuery.ajax({
                type: "POST",
                url: ajax_url,
                async: false,
                data:
                        {
                            resource_elem_id: jQuery("#" + resource_elem_id).val(),
                        }
            }).done(function(response) {
                jQuery("#" + update_element_id).html(response);
            });
        }
    },
    go_history: function() {
        var previous_page = document.referrer;
        window.location = previous_page;
    },
    popupwindow: function(url, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    },
    openColorBox: function(obj) {
        jQuery(obj).colorbox({width: "60%", height: "80%", iframe: true});
    },
    openColorBoxNoIFrame: function(obj) {
        jQuery(obj).colorbox({width: "60%", height: "80%"});
    },
    printPreview: function(obj) {
        var left = (screen.width / 2) - (700 / 2);
        var top = (screen.height / 2) - (490 / 2);
        var width = (screen.width / 2) * 1.2;
        var height = (screen.height / 2);
        var str = "height=" + height + ",scrollbars=yes,width=" + width + ",status=yes,";
        str += "toolbar=no,menubar=no,location=no,resizable=false,left=" + left + ",top=" + top + "";
        window.open($(obj).attr("href"), "popup", str);
    },
}
