var checkBoxes = $('input[type=checkbox]');
var radioBtns = $('input[type=radio]');

$(document).ready(function() {

    if (checkBoxes.length) {
        checkBoxes.each(function(){
            var el = $(this);
            checkboxWrap = el.parents('.checkbox_wrap');
            // add wrapper if not added
            if (!checkboxWrap.length) {
                checkboxWrap = $('<span/>', {'class':'checkbox_wrap'})
                el.wrap(checkboxWrap);
            }

            // checked/uncheck
            if (el.is(':checked')) {
                checkboxWrap.addClass(classActive)
            }
            
            // evaluate on change event
            // el.click(function(){
                // checkboxWrap = el.parents('.checkbox_wrap');
                // checkboxWrap.toggleClass(classActive)
            // })

            el.change(function(){
                checkboxWrap = el.parents('.checkbox_wrap');
                if (el.is(':checked')) {
                    checkboxWrap.addClass(classActive)
                } else {
                    checkboxWrap.removeClass(classActive)
                }
            });          
        })
    }
    
    $(document).on('click', '.panel-heading span.clickable', function(e){
        var $this = $(this);
        if(!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel').find('.panel-body').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel').find('.panel-body').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });
    
   $("#searchdropdown li a").click(function() {
       $("#mainsearch").html($(this).text() + ' <span class="caret"></span>');
       $("#mainsearch").val($(this).text());
   });
   
   $(document).on('submit','#searchform',function() {
       if ($("#mainsearch").val() == 'Ads') {
           $('#searchform').attr("action", base_url+"/search");
       } else if ($("#mainsearch").val() == 'Users') {
           $('#searchform').attr("action", base_url+"/users/search");           
       } 
   });

});


var ProgressBar;

ProgressBar = ProgressBar || (function () {
    var progress_div = '<div id="eshtihar-progress-bar" class="eshtihar-overlay"><div class="overlay-message"><img src="'+base_url+'/img/progress.gif" /></div></div>';
    return {
        show: function() {
            $("body").append(progress_div);
        },
        hide: function () {
            $('#eshtihar-progress-bar').remove();
        },
    };
})();



function resetForm(form_name, reset_hidden) {   

    var reset_hidden_val = false;
    
    if (typeof(reset_hidden)==='undefined') {
        reset_hidden_val = true;
    }
    
    $('#ajax-error-messages').html('');

    $('#'+form_name)[0].reset();
    
    if (reset_hidden_val) {
        $('#'+form_name+' input[type=hidden]').val('');    
    }
    
    $('#'+form_name+' label.error-message').hide();
    $('#'+form_name+' .error-message').removeClass("error-message");
}


function setAttributesValidationRules() {
    
    // Required validation
    $(".attrib_required").each(function() {
                
        var label = $(this).closest('.form-group').children('label').html();
        
        if (label.indexOf('*:') > -1) {
            label = label.replace('*:', '');
        }
        
        var message = 'Please enter '+label.toLowerCase()+'.';
        if ($(this).is("select")) {
            message =  'Please select '+label.toLowerCase()+'.';   
        }
        
        $(this).rules('add', {
            required: true,
            messages : {
                required : message
            }
        });
    });
    
    // Digits validation
    $(".num_digits").each(function() {
                
        var label = $(this).closest('.form-group').children('label').html();
        
        if (label.indexOf('*:') > -1) {
            label = label.replace('*:', '');
        }
        
        var message = 'Please enter valid '+label.toLowerCase()+'.';
        if ($(this).is("select")) {
            message =  'Please select valid '+label.toLowerCase()+'.';   
        }
        
        $(this).rules('add', {
            digits: true,
            messages : {
                digits : message
            }
        });
    });
}

function save_url(url) {
    url = typeof url !== 'undefined' ? url : '';
    
    req = base_url+"/save-url";
    var request = $.ajax({
        url: base_url+"/save-url",
        type: 'GET',
        dataType: 'json',
        data: {url: url},
        async: false
    });

    request.done(function(json) {
    });
}

