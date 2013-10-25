/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

add_mode = true;

/**
 * Loading row form fields of child form and make it part of parent form.
 */
function add_new_child_row(u, module_name, field_container, field_row, show_grid)
{
 
    jQuery('#loading').toggle();
    //    alert(field_container);
    jQuery.ajax({
        url: u,
        success: function(response)
        {
            jQuery('#'+module_name+' .subform').css('display', 'block');
               
            /* 
             * Normally when page is loaded add_mode is true. 
             * When user click at edit button add_mode become false because there is a complete seprate code for
             * loading field_container in edit mode under edit button.
             * And when again add button is pressed, first field_container div is set to null and then it is updated by appending
             * 
             * In case of add we append the div - In case of edit we replace the div
             * 
             */
            if(add_mode == false)
                jQuery('#'+field_container).html('');
            
            /* append div with response, display is none for responsed div */
            jQuery('#'+field_container).append(response);
             
            
            /* Now animate response */
            jQuery('#'+field_container + ' .' + field_row).last().animate({
                opacity : 1, 
                left: '+50', 
                height: 'toggle'
            });
            
            /* Grid should be viewable in parent view mode */
            if(show_grid == true && jQuery('#'+module_name+' .child').is(':hidden'))
            {
                //                jQuery('#'+module_name+' .child').show('slow');
                jQuery('#'+module_name+' .child').animate({
                    opacity : 1, 
                    left: '+50', 
                    height: 'toggle'
                });
            }
          
            jQuery('#loading').toggle();
            add_options();
            add_mode = true;
        }
    });
}
/**
 *  for adding sub child
 * @param {type} u
 * @param {type} module_name
 * @param {type} field_container
 * @param {type} field_row
 * @param {type} show_grid
 * @returns {undefined}
 */
function add_newSub_child_row(u, module_name, field_container, field_row, show_grid)
{
 
    jQuery('#loading').toggle();
    //    alert(field_container);
    jQuery.ajax({
        url: u,
        success: function(response)
        {
            jQuery('#'+module_name+' .subform').css('display', 'block');
               
            /* 
             * Normally when page is loaded add_mode is true. 
             * When user click at edit button add_mode become false because there is a complete seprate code for
             * loading field_container in edit mode under edit button.
             * And when again add button is pressed, first field_container div is set to null and then it is updated by appending
             * 
             * In case of add we append the div - In case of edit we replace the div
             * 
             */
            if(add_mode == false)
                jQuery('#'+field_container).html('');
            
            /* append div with response, display is none for responsed div */
            jQuery('#'+field_container).append(response);
             
            
            /* Now animate response */
            jQuery('#'+field_container + ' .' + field_row).last().animate({
                opacity : 1, 
                left: '+50', 
                height: 'toggle'
            });
            
            /* Grid should be viewable in parent view mode */
            if(show_grid == true && jQuery('#'+module_name+' .child').is(':hidden'))
            {
                //                jQuery('#'+module_name+' .child').show('slow');
                jQuery('#'+module_name+' .child').animate({
                    opacity : 1, 
                    left: '+50', 
                    height: 'toggle'
                });
            }
          
            jQuery('#loading').toggle();
            add_options();
            add_mode = true;
        }
    });
}

jQuery(".grid_fields").live('mouseover',function(){
    jQuery(this).find('.del-icon').show();
});
jQuery(".grid_fields").live('mouseout',function(){
    jQuery(this).find('.del-icon').hide();
});
jQuery(".main").live('mouseover',function(){
    jQuery(this).find('.del-icon').show();
});
jQuery(".main").live('mouseout',function(){
    jQuery(this).find('.del-icon').hide();
});


/**
 * 
 * Delete form fields Loaded by ajax in child forms or parent forms
 * echo CHtml::link('Cancel', '#', array('onClick' => 'delete_fields(this, 3, "#grand_parent", "#check_child"); return false;'))
 * 
 * Last parameter check_child is used to hide grand parent if that check_field class is found in grand parent)
 */
function delete_fields(elm, num_of_parents, grand_parent, check_child)
{
    parent=jQuery(elm).parent();
    //    last_parent_child=jQuery(parent).children().last();
    //    if(jQuery(last_parent_child).attr("class")=="attachment_index_class")
    //    {
    //        delete_attachments(last_parent_child); 
    //    }
    
    if(check_child == "") check_child = ".grid_fields";

    /* Get exact delete button */
    element = jQuery(elm);
   
    elementTitle=jQuery(elm).attr('title');
    elememGrandParent=jQuery(elm).parent().parent().parent().attr("id");

    elememParent=jQuery(elm).parent().parent();
    
    
    /* go to the parent div which should be removed */
    for(count = 1; count <= num_of_parents; count++)
    {
        element = jQuery(element).parent();
    }

    /* animate div */
    jQuery(element).animate(
    {
        opacity: 0.25, 
        left: '+=50', 
        height: 'toggle'
    }, 500,
    function() {
        /* remove div */
        jQuery(element).remove();
        /* if it is required to hide grand parend */
        if(grand_parent != '' && jQuery(grand_parent).find(check_child).length == 0)
        {
            jQuery(grand_parent).animate({
                height: 'toggle'
            }, 400);
        }
    });
  
/**
     * this portion will be used in labour field forced gc and sc
     *  for calcualation 
     */
       

//emptyResultsChilds("sc","field_force_labor");
    
}

/**
     *  define jspicker for logic
     */
function defineJspicker(model,index,attrbute)
{
    element_a=model+"_"+index+"_"+attrbute;
    jQuery("#"+element_a).jPicker(
    {
        images:
        {
            clientPath: js_basePath+"/packages/jcolorpicker/images/",
        }
    }     
    );
}
/**
 *  geernate html for uploadify
 *  
 */
function generateUploadifyHtml(name,img_url,deleted_url,type)
{
    deleted_url+="?type="+type+"&img="+name;
    html='<div class="pointer-mouse" style=";float:left;margin-right:5px;margin-top:5px;">';
    html+='<div class="img_hover">';
    html+='<img src="'+img_url+name+'" style="width:100px;height:100px" onclick="dozoomer(this)" />';
    html+='</div>';
    html+='<input type="hidden" name="uploaded_image[]" value="'+name+'" />';
    html+='<input type="text" name="image_title[]" value="Title"  />';
    html+='<input class="image_radio" type="radio" name="model_image" value="'+name+'" title="Primary image" alt="Primary image" />';
    html+='<div class="clear"></div>';
    html+='<a href="#deltetimage" onclick="deletetimage(this)">Delete</a>';
    html+='<input type="hidden" name="href_path" value="'+deleted_url+'" />';
    html+='</div>';
    
    return html;
}

function deletetimage(obj)
{
    if(confirm("Are you sure you want to delete this image"))
    {
        deleted_url=jQuery(obj).next().val();
        /**
     * 
     */
        if(deleted_url.search("real")!=-1)
        {
            elem_id=jQuery(obj).prev().prev().val();
            deleted_url+="&id="+elem_id;
        }
        jQuery.ajax({
            type: "POST",
            url: deleted_url,
 
        }).done(function( msg ) {
            jQuery(obj).parent().remove();
            if(jQuery.trim(jQuery(".image_content").html())=="")
            {
                jQuery(".uploadify_log-details").hide();
            }
        });
    }
    
   
  
}
function dozoomer(obj)
{        
    jQuery("#zoomer").attr("href",jQuery(obj).attr("src"));
    jQuery("#zoomer").trigger("click");
}

/**
 *  json with filling
 */
function fillDropDown(json,elemd_id)
{
    jQuery("#"+elemd_id).html("");
    for(obj in json)
    {
        htm="<option value='"+obj+"'>"+json[obj]+"</option>";
        jQuery("#"+elemd_id).append(htm);
    }
}

function add_options()
{
    if(typeof(jQuery("#options").val())!="undefined" )
    {
        default_options = jQuery("#options").val();
        default_options = default_options.split(",");
        if(default_options.length>0)
        {
            i = 0;
            jQuery(".grid_fields").each(function()
            {
                if(typeof(default_options[i])!="undefined")
                {
                    jQuery(this).children().eq(0).children().eq(0).val(default_options[i]);
                    jQuery(this).children().eq(1).children().eq(0).val(default_options[i]);
                    if(i==0)
                    {
                        jQuery(this).children().eq(2).children().eq(1).attr("checked","checked");     
                    }
                }
                else
                {
                    //jQuery(this).children().eq(0).children().eq(0).val("");
                    //jQuery(this).children().eq(1).children().eq(0).val("");
                }
                i++;
            }) 
        }
          
    }
   
}