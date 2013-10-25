/* Goi to history page -1. */
function go_history(){
    var previous_page=document.referrer;
    window.location=previous_page;
}

var urlParams = {};
(function () {
    var e,
    a = /\+/g,  // Regex for replacing addition symbol with a space
    r = /([^&=]+)=?([^&]*)/g,
    d = function (s) {
        return decodeURIComponent(s.replace(a, " "));
    },
    q = window.location.search.substring(1);

    while (e = r.exec(q))
        urlParams[d(e[1])] = d(e[2]);
})();

$(document).ready(function()
{
    /**
     * Display module's child when found # value in address if it is 'all'
     * then display all childs
     */
    var relative = document.location.hash.substr(1);
    if(relative && relative != "all")
    {
        $("."+relative).slideDown("slow");
        $("#"+relative+"-plus").attr("class", "child-title-with-minus");
    }
    else if(relative == "all")
    {
        $(".child").slideDown(1500);
        $(".child-title-with-plus").attr("class", "child-title-with-minus");
        $(".attachments").css("display", "block");
    }
    
    /**
    * Hide Show Button is used to hide or show left menu.
    * 
    */
    $('.hideShow').click(function(){
        if($('.span-5').css('display') == "none")
        {
            imgDiv = $(this).parent();
            imgDiv.toggle();
            //            $('.span-18').animate({width: '790'}, 400);
            $('.span-18').attr({
                'style' : 'width:790px !important'
            });
            $('.span-5').animate({
                width: 'toggle'
            }, 400, function(){
                imgDiv.css("margin-left", "178px");
                imgDiv.toggle();
            });
            
            $(this).attr("class", "hideImage");
        }
        else
        {
            imgDiv = $(this).parent();
            imgDiv.toggle();
            $('.span-5').animate({
                width: 'toggle'
            }, 400, function() {
                $('.span-18').attr({
                    'style' : 'width:980px !important'
                });
                imgDiv.css("margin-left", "0px");
                imgDiv.toggle();
            });
            $(this).attr("class", "showImage");
        }
        return false;
    });

    /**
     * This area only for 
     * colobox to increase 
     * the size in color mode
     */
    p=parent.document.getElementById("resize_cb");
       
    if(urlParams['colorbox']!=undefined)
    {   
        $(p).trigger("click");   
    }
        
});
/**
 * Simple ajax call with required url and update div id name
 * @reqUrl ajax url
 * @update update div id/class
 * @update_type return value/html/text or different function perform on this condition
 * @callback callbacks
 */
function myAJAX(reqUrl, update, update_type, callback)
{
    /* Display loading div */
    $('#loading').show();
    $.ajax
    ({
        url: reqUrl, 
        cache: false,
        //data:"id="+id,
        success: function(response)
        {
            if(update_type == "html") $(update).html(response);
            else if (update_type == "text") $(update).text(response);
            else if (update_type == "val") $(update).val(response);
            /* Remove Content : Just remove html content */
            else if (update_type == "RemCon")
            {
                //                $(update).hide();
                $(update).fadeOut(250, function() {
                    $(update).remove()
                });
            }
            $("#Issue_type").focus();
            $('#loading').hide();
            /* Call Function  */
            if(callback != ""){
                window[callback]();
            }
        }
    });
}
/****************************************************************************************************
 * Child Container/form functions
 *****************************************************************************************************/

/**
 * 
 * Delete form fields Loaded by ajax in child forms or parent forms
 * echo CHtml::link('Cancel', '#', array('onClick' => 'delete_fields(this, 3, "#grand_parent", "#check_child"); return false;'))
 * 
 * Last parameter check_child is used to hide grand parent if that check_field class is found in grand parent)
 */
function delete_fields(elm, num_of_parents, grand_parent, check_child)
{
    parent=$(elm).parent();
    
    if(check_child == "") check_child = ".grid_fields";

    /* Get exact delete button */
    element = $(elm);
   
    elementTitle=$(elm).attr('title');
    elememGrandParent=$(elm).parent().parent().parent().attr("id");

    elememParent=$(elm).parent().parent();
    
    
    /* go to the parent div which should be removed */
    for(count = 1; count <= num_of_parents; count++)
    {
        element = $(element).parent();
    }

    /* animate div */
    $(element).animate(
    {
        opacity: 0.25, 
        left: '+=50', 
        height: 'toggle'
    }, 500,
    function() {
        /* remove div */
        $(element).remove();
        /* if it is required to hide grand parend */
        if(grand_parent != '' && $(grand_parent).find(check_child).length == 0)
        {
            $(grand_parent).animate({
                height: 'toggle'
            }, 400);
        }
    });
  
    /**
     * this portion will be used in labour field forced gc and sc
     *  for calcualation 
     */
   
    if(elementTitle)
    {
        setTimeout("emptyResultsChilds(elementTitle,elememGrandParent);",500);
        
    }    
 
//emptyResultsChilds("sc","field_force_labor");
}


/**
 * Delete attachment field row from form.
 * Send an AJAX call to remove files, uploaded any, from temp and remove that
 * field from form
 * 
 * @elm
 * delete button/link element, on the bases of find current attachment field
 * which is required to remove from form.
 */
function delete_attachment_fields(elm, reqUrl)
{
    /* Display loading div */
    $('#loading').toggle();
    $.ajax
    ({
        url: reqUrl, 
        cache: false,
        //data:"id="+id,
        success: function(response)
        {
            /**
             * response.search will return 0 or -1
             * 0 : it means the searched value is found
             * -1 : means searched value not found
             */
            res = response.search("1");
            
            if(res == 0)
            {
                /* Current Attachment field which is required to remove */
                current_att_field = $(elm).parent().parent().parent();

                /* 
                * Grand parent (included heading/title) 
                * In case of last field normally titles should be hidden and when user 
                * press again add new link then it should be appear again
                */
                grand_parent = $(elm).parent().parent().parent().parent().parent().parent();
                
                /**
                 * In detail view mode another parent form tage involves but in create mode it dosn't
                 * So to control that difference we do this
                 */
                if($(grand_parent).find($('form')).length == 1)
                    grand_parent = $(elm).parent().parent().parent().parent().parent().parent();
                else
                    grand_parent = $(elm).parent().parent().parent().parent().parent();

                /* 
                * If form has more than one filed then just remove fields 
                * If form has only one last field left then hide attachment form area 
                * and remove field.
                */
                if($(grand_parent).find(".att-fields").length > 1)
                {
                    /* animate div */
                    $(current_att_field).animate(
                    {
                        opacity: 0.25, 
                        left: '+=50', 
                        height: 'toggle'
                    }, 500,
                    function() {
                        $(current_att_field).remove();
                    });
                }
                else
                {
                    /* Hide attachment heading/titles and remove regarding field */
                    $(grand_parent).animate(
                    {
                        opacity: 1, 
                        left: '+=50', 
                        height: 'toggle'
                    }, 500,
                    function() {
                        $(current_att_field).remove();
                    });

                }
            }
            else
            {
                alert("Problem in removing files from temp");
            }
            
            //            $("#Issue_type").focus();
            $('#loading').toggle();
        },
        error: function(html){
            $('#loading').toggle();
            alert("Exception generated.");
        }
    });
}

/* In case of add we append the div - In case of edit we replace the div */
add_mode = true;

/**
 * Loading row form fields of child form and make it part of parent form.
 * @param String u
 * @param String module_name
 * @param String field_container
 * @param String field_row
 * @param Boolean show_grid
 * @param String preLoaderText
 * @param Boolean loadBlock
 *  When user want to add a complete block of fields instead of adding one by one by click add new
 *  then we need to make it true i.e see ProjectReports/_project_crews_list.php
 */
function add_new_child_row(u, module_name, field_container, field_row, show_grid, preLoaderText, loadBlock)
{
    if(preLoaderText)
        $('#loading').html(preLoaderText);
    else 
        $('#loading').html("Please Wait.");
    $('#loading').slideToggle(200);
    
    $.ajax({
        url: u,
        success: function(response)
        {
            $('#'+module_name+'-plus').attr('class', 'child-title-with-minus');
            $('#'+module_name+' .subform').css('display', 'block');
               
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
                $('#'+field_container).html('');
            
            /* append div with response, display is none for responsed div */
            $('#'+field_container).append(response);
           
            
            
            /**
             * When block is being loaded insted of single field row
             * then we dont have to animate/show last added row in the form/container
             * We animate complete set of it that is being added in container.
             */
            if(loadBlock && loadBlock == true)
            {
                /* Now animate response */
                $('#'+field_container + ' .' + field_row).slideDown();
            }
            else
            {
                /* Now animate response */
                $('#'+field_container + ' .' + field_row).last().animate({
                    opacity : 1, 
                    left: '+50', 
                    height: 'toggle'
                });
            }
            
            /* Grid should be viewable in parent view mode */
            if(show_grid == true && $('#'+module_name+' .child').is(':hidden'))
            {
                $('#'+module_name+' .child').animate({
                    opacity : 1, 
                    left: '+50', 
                    height: 'toggle'
                });
            }
            //using for checked first element of radio button of any module etc (company)
            making_radiobuttonactive(field_container,field_row,'field','input:radio',u)     
            
            $('#loading').slideToggle(200);
                
            add_mode = true;
        }
    });
}

/**
 * used for making your own custom element to active at first child loading
 * parentelement is #main grid 
 * childfirst first child 
 * fieldclass that containg the element to active
 * element particular element to do active
 */
function making_radiobuttonactive(parentelement,childfirst,classelement,input_type,url)
{
        
    if($("#"+parentelement+" ."+childfirst).length==1 && url.search("create")!=-1)
    {
        $("#"+parentelement+" ."+childfirst+":first-child"+" ."+classelement+" "+input_type).attr("checked","checked");
    }    
}

// js script for autoloading occurs a trigger event       
function autoloading(cssclass,parent,action)//for create
{
    
    //if($("#"+parent+" :first-child").length==0 && action=="create")
  
    if($.trim($("#"+parent).html())=="" && action=="create")
    {
        $("."+cssclass).trigger("click");
    }    
  
}
 
// js script for autoloading occurs a trigger event  for custom length     
function autoloading_custom(cssclass,parent,action,length)//for create
{
    
    //if($("#"+parent+" :first-child").length==0 && action=="create")
      
    if($.trim($("#"+parent).html())=="" && action=="create")
    { 
        for(i=0;i<length;i++)
        {
            $("."+cssclass).trigger("click");
        }
    }   
          
      
  
}
// js script for autoloading occurs a trigger event for view 
 
function autoloadingview(cssclass,action)
{ 
    var element_id=window.location.hash;
    if(action=="view" && element_id!="")
    {         
    // $(".safety-report-summary-button").trigger("click");
    //$("."+cssclass).trigger("click");
    
    //$(element_id+" a."+cssclass).trigger("click");
    }    
  
}
function js_str_replace (search, replace, subject, count) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order
    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'
    var i = 0,
    j = 0,
    temp = '',
    repl = '',
    sl = 0,
    fl = 0,
    f = [].concat(search),
    r = [].concat(replace),
    s = subject,
    ra = Object.prototype.toString.call(r) === '[object Array]',
    sa = Object.prototype.toString.call(s) === '[object Array]';
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }

    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];
}

function windowscroll(obj)
{
    $("html").animate({
        scrollTop: $(document).height()
    }, "fast");
}


function readyScript()
{
    $("input:checkbox").attr("checked",false);
    //Examples of how to assign the ColorBox event to elements
    $(".add-label").live('click',function(e)
    {
        e.preventDefault();
       
        $(this).colorbox();
    })
    
}

function checkAll(obj)
{
    if(($(obj)).is(":checked"))
    {
        $("input:checkbox").attr("checked",true);
    }
    else
    {
        $("input:checkbox").attr("checked",false);
    }
}

function labelform(urlaction,obj)
{
    
    $.ajax({ 
        url: $(obj).attr('action'),
        data: $(obj).serialize(),
        type: "POST",
        success: function(data){
            if(data.search('false')!=-1)
            {
                //                alert(url);
                window.location=urlaction;
            }
            else
            {
                $("#cboxContent").html(data);
            }
        }
    });
    
//   return false;     
}
function moveEmail(urlaction,moveurl,obj)
{
    var emails="";
    $("input:checked").each(function(){
        if($(this).attr("type")=="checkbox" && $(this).val()!=0)
        {
            emails+=$(this).val()+",";
        }

    });
    emails=emails.substring(0,(emails.length-1));
    //    if(emails!="" && $(obj).val()==0)
    //        alert("Please Select Correct Label");
    //    else if(emails=="" && $(obj).val()!="")
    //        alert("Please Select a Email to move");
    //    else
    if(emails!="" && $(obj).val()!="")
    {
        $.ajax({
            url: moveurl,
            data:"emails="+emails+"&label_id="+$(obj).val(),
            type: "POST",
            success: function(data){
                if($.trim(data)=='true')
                {
                    window.location=urlaction;
                }
            }
        });
    }
   
}

function editLabel(obj,url,event)
{
    
    if($("#EmailLabel_label").val()!="" && $("#EmailLabel_label option:selected").text()!="Sent" && $("#EmailLabel_label option:selected").text()!="Inbox")
    {
        url+="&id="+$("#EmailLabel_label").val();
        $(obj).attr('href',url);
        $(obj).addClass("add-label");
    //         $(obj).colorbox();
    }
       
    else
    {
        alert("Please Select Label to Change");
        $(obj).attr('href',"javascript:void(0);");
        $(obj).removeClass("add-label");
        $(obj).removeClass("cboxElement");
    }
    
}

function IsNumeric(input)
{
    return ( (typeof x === typeof 1) && (null !== x) && isFinite(x) );
}

function updateTotal(obj,module)
{
    update_class=$(obj).attr('alt')+'_total';
    fieldclass=module+"_"+$(obj).attr('alt');
    
    var total=0;
    var total2=0;
    $("."+fieldclass).each(function()
    {
        val=$(this).val();
        if(val=='' || isNaN(val)==true)
        {
            //alert('value is empty or not a number'); 
            val=0;
        }
        if(val!=0)
        {
            val=parseInt(val);
        }    

        total2+=parseInt(val)
    })
   
    $("#parent_"+module+" ."+update_class).html(total2);
  
       
}
// will be used for auto calculation
function emptyResultsChilds(module,parent)
{
    if(parent.search('_fields')==-1)
    {
        parnet="#"+parent+"_fields .grid_fields";
    }    
    else
    {
        parnet="#"+parent+" .grid_fields";  
       
    }
   
    if($(parnet).length>0)
    {
        $(".blur_total_"+module).trigger("blur").delay(400);
    }    
}

/* Hide Show delete buttons */
$(".grid_fields").live('mouseover',function(){
    $(this).find('.del-icon').show();
});
$(".grid_fields").live('mouseout',function(){
    $(this).find('.del-icon').hide();
});
$(".main").live('mouseover',function(){
    $(this).find('.del-icon').show();
});
$(".main").live('mouseout',function(){
    $(this).find('.del-icon').hide();
});


/*This is for email linking*/
$(document).ready(function()
{
    
    $('#_inbox-form_association').live('submit',function(){
        
        var submityes=false;
        $('.email_checkbox').each(function()
        {
            if($(this).is(':checked')==true)
            {
                submityes=true;
            }

        });
        if(submityes==true)
        {
            $('.loading_linking').show();
            $('#loading_colorbox').show();
            $("#add_btn_ajax_assocation").attr('disabled', true);
            $.ajax({
                url: $(this).attr('action'),
                data: $(this).serialize(),
                type: "POST",
                success: function(data){
                    $("#cboxLoadedContent").html(data);
                    $('#loading_colorbox').hide();
                    $('.loading_linking').hide();
                //$("#add_btn_ajax").attr('disabled', false);
                }
            });
        }
        else
        {
            alert("No Selection Found");
        }
            
        return false;
    });
})
 
/**
 *  pop up window open
 */ 
function openwindow(id,url)
{
    var left = (screen.width/2)-(700/2);
    var top = (screen.height/2)-(490/2);
    var width=(screen.width/2)*1.2;
    var height=(screen.height/2);
    var str="height="+height+",scrollbars=yes,width="+width+",status=yes,";
    str+="toolbar=no,menubar=no,location=no,resizable=false,left="+left+",top="+top+"";
    window.open(url,"popup",str);
  
}


//// use for document parent association checbox to check child checboxes
$(document).ready(function(){  

    if($(window).width()<905)
    {
        $(".menu").hide();
        $('.operations').hide();
        $(".button-column").hide()
        $(".print_link_btn").hide();
        $("#footer").hide();
                
    }
  
});

function  un_checkboxes()
{
    $('input').attr('checked', false);
}
function getGridId(obj)
{   
    var par_table=$(obj).closest("table");
    if($(obj).is(":checked"))
    {
        $(par_table).find("input:checkbox").each(function(){
            $(this).attr('checked',true);
       
        });
    }
    else
    {
        $(par_table).find("input:checkbox").each(function(){
            $(this).attr('checked',false);
       
        });  
    }
   
}

function refreshParent() {
    window.location.reload();

}
/**
 * use for labor rates
 */
function calculateSubRate(obj,nextdepth)
{
    var total=0;
    
    elemF=$(obj).parent().siblings().get(nextdepth);
    var sub_hours=0;
   
    var total_hours_rate=0;
    var sub_total;
   
    total_hours_rate=parseFloat($(obj).prev().val());     
    sub_hours=parseFloat($.trim($(obj).val()));      
    sub_total=total_hours_rate*sub_hours;
    
    if(isNaN(sub_total))
    {
        sub_total=0;
    }

    all_text_fields=$(obj).parent().parent().find("input[type=text]");
    
    $(all_text_fields).each(function(){        
        
        if(!isNaN($(this).val())  && $(this).val()!="" && $(this).attr("class")!="readonly" && $(this).attr("id")!=$(obj).attr("id"))
        {
            
            total_hours_rate+=parseFloat($(this).prev().val());
            
            sub_hours+=parseFloat($.trim($(this).val()));
            
            sub_total+=parseFloat($(this).prev().val())*parseFloat($.trim($(this).val()));
        }
        
        
        
    });
    $(elemF).children(":first").val(sub_hours);
    $(elemF).next().children(":first").val(Math.round(sub_total*100)/100);
}
function calculateEquipRate(obj,nextdepth,pstr)
{
    var total=0;
    
    elemF=$(obj).parent().siblings().get(nextdepth);
    var sub_hours=0;
   
    var total_hours_rate=0;
    var sub_total;
   
    rate=parseFloat($(obj).prev().val()); 
    
    current_val=parseFloat($.trim($(obj).val()));   
    
    sub_total=rate*current_val;

    
    if(isNaN(sub_total))
    {
        sub_total=0;
    }

    all_text_fields=$(obj).parent().parent().find("input[type=text]");
    
    $(all_text_fields).each(function(){        
        
        if(!isNaN($(this).val())  && $(this).val()!="" && $(this).attr("class")!="readonly" && $(this).attr("id")!=$(obj).attr("id"))
        {

            sub_total=sub_total*parseFloat($.trim($(this).val()));
        }
        
        
        
    });
    $(elemF).children(":first").val(Math.round(sub_total*100)/100);
}
function calculateEquipmentTotal()
{
    var num_units=0;
    var total_hours=0;
    var total=0;
    $("#weekly_inspection_report_equipment_summary_fields").children(".grid_fields").children().each(function(){
        if($(this).find("input[type=text]").length!=0)
        {
            obj=$(this).find("input[type=text]");
            idtext=$(obj).attr("id");
            if(idtext.indexOf("_number_of_units")!=-1)
            {
                num_units+=parseFloat($(obj).val());
            }
            if(idtext.indexOf("_hours")!=-1)
            {
                total_hours+=parseFloat($(obj).val());
            }
            if(idtext.indexOf("_total_amount")!=-1)
            {
                if($(obj).val()!=0)
                {
                    total+=parseFloat($(obj).val());
                }
            }
        }
       
    });
    
    $("#num_units").html(num_units);
    $("#total_equipment_hours").html(total_hours);
    $("#total_equipment").html(Math.round(total*100)/100);
    
}
/* for labor rate*/
function CalculateTotal()
{
    var total_hours=0;
    var total=0;
    var st=0;
    var ot=0;
    var dt=0;
    var idtext;
    $("#weekly_inspection_report_labor_summary_fields").children(".grid_fields").children().each(function(){
        if($(this).find("input[type=text]").length!=0)
        {
            obj=$(this).find("input[type=text]");
            idtext=$(obj).attr("id");
            if(idtext.indexOf("_st")!=-1)
            {
                st+=parseFloat($(obj).val());
            }
            if(idtext.indexOf("_dt")!=-1)
            {
                dt+=parseFloat($(obj).val());
            }
            if(idtext.indexOf("_ot")!=-1)
            {
                ot+=parseFloat($(obj).val());
            }
            if(idtext.indexOf("_total_amount")!=-1)
            {
                if($(obj).val()!=0)
                {
                    total+=parseFloat($(obj).val());
                }
            }
        }
       
    });

    total_hours=st+ot+dt;
    $("#st").html(st);   
    $("#ot").html(ot);
    $("#dt").html(dt);
    $("#total_hours").html(total_hours);
    $("#total").html(Math.round(total*100)/100);
}

//js for permission grid

function showPermission(elem,cssclass,maximg,minimg)
{
    $("."+cssclass).toggle();
    if($("."+cssclass).is(':visible')==true)
    {
        $(elem).children().attr("src",minimg);
    }
    else
    {
        $(elem).children().attr("src",maximg);      
    }
   
    
}
function showPermissionHeader(elem,maximg,minimg)
{
    $(".parent_css").toggle();
    if($(".parent_css").is(':visible')==true)
    {
        $(elem).children().attr("src",minimg);
    }
    else
    {
        $(elem).children().attr("src",maximg);      
    }
}

    
$("#quick_filter a.minimize").live('click',function(){
    if($(this).parent().find("ul li").length!=0)
    {
        $(this).removeClass('minimize');
        $(this).addClass('maximize');
        $(this).parent().find("ul").hide();
    }
    return false;
});
    
/**
 * Utility funciton to get the query parameters from the url
 */    
function getQuerystring(url)
{
    var query_start_index=url.indexOf("?");
    var url_query_string=url.substring(query_start_index,url.length);
    var bulk_form_query_string =url_query_string.replace(/\?/g,"");
    var new_form_query_string=bulk_form_query_string.replace(/%5B/g,"[");
    var query_string=new_form_query_string.replace(/%5D/g,"]");
    return query_string;
}
/**
 * Utility funciton to get the specific query parameter from the querystring
 */  

function checkQeuryValue(querystring,param)
{
    paramPos=querystring.indexOf(param);
    if(paramPos!=-1)
    {
        tempQueryString=querystring.substring(paramPos,querystring.length);
        andPos=tempQueryString.indexOf("&");
        paramQueryString=tempQueryString.substring(0,andPos);
        opParamPos=paramQueryString.indexOf("=");
        resultParam=paramQueryString.substring(opParamPos+1);
        console.log(resultParam);
        return resultParam;
    }
    else
    {
        return "";
    }
}

/**
 * Hide Show Module's Children by clicking add new
 */
function hideShowModuleChild(relationName)
{   
    $('.' +relationName + '-button').slideto({target : '#'+relationName, speed  : 1000 });
    $('.' +relationName + '-button').click(function(){
        $('#' + relationName + '-plus').attr('class', $('#' + relationName + '-plus').attr('class') == 'child-title-with-plus' ? 'child-title-with-minus' : 'child-title-with-plus');
        $('.' + relationName).animate(
        {
            opacity: 'toggle', 
            left: '+=50', 
            height: 'toggle'
        }, 500, function(){}
        );
        return false;
    });
}
