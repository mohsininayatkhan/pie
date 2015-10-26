$(document).ready(function() {
    
    $('.hyperspan').hover(function() {
        $(this).closest('.ads-list').css("background-color", "#f3f8f9");
    });
    
    $('.hyperspan').mouseleave(function() {
        $(this).closest('.ads-list').css("background-color", "");
    });
    
    $("#frmAdMessage").validate({
        rules : {
            sendername: {
                required : true
            },
            senderemail: {
                required : true,
                email : true
            },
            messagetext: {
                required : true
            }
        },
        messages : {
            sendername : {
                required : 'Please enter title.',
            },
            senderemail : {
                required : 'Please enter email.',
                email : 'Please enter valid email.'
            },
            sendermessage : {
                required : 'Please enter message.'
            }
        }
    });
    
    var options = { 
            target:        '#output1',   // target element(s) to be updated with server response 
            beforeSubmit:  formMessageRequest,  // pre-submit callback 
            success:       formMessageResponse,  // post-submit callback
            type: 'post', 
            dataType:  'json' 
    };
    
    $('#frmAdMessage').ajaxForm(options);   
    
    function formMessageRequest(formData, jqForm, options)
    {
        var queryString = $.param(formData);
        return;
    }
        
    function formMessageResponse(responseText, statusText)
    {
        if (statusText == 'success') {
            if (responseText.status == 'ERROR') {
                html = '<div role="alert" class="alert alert-danger">';
                $.each(responseText.messages, function(key, value) {
                    html += value+'<br>';    
                });
                html += '</div>'; 
                $('#ajax-error-messages').html(html);
            } else {
                $('#exampleModal').modal('hide');
                toastr.success('Your message sent successfully!');
            }
        }
    };
    
    $("#frmAdReport").validate({
        rules : {
            sendername: {
                required : true
            },
            senderemail: {
                required : true,
                email : true
            },
            messagetext: {
                required : true
            },
            report: {
                required : true
            }            
        },
        messages : {
            sendername : {
                required : 'Please enter title.',
            },
            senderemail : {
                required : 'Please enter email.',
                email : 'Please enter valid email.'
            },
            sendermessage : {
                required : 'Please enter message.'
            },
            report: {
                required : 'Please select report type.'
            }             
        }
    });
    
    var options = { 
            target:        '#output1',   // target element(s) to be updated with server response 
            beforeSubmit:  formReportRequest,  // pre-submit callback 
            success:       formReportResponse,  // post-submit callback
            type: 'post', 
            dataType:  'json' 
    };
    
    $('#frmAdReport').ajaxForm(options);   
    
    function formReportRequest(formData, jqForm, options)
    {
        var queryString = $.param(formData);
        return;
    }
        
    function formReportResponse(responseText, statusText)
    {
        if (statusText == 'success') {
            if (responseText.status == 'ERROR') {
                html = '<div role="alert" class="alert alert-danger">';
                $.each(responseText.messages, function(key, value) {
                    html += value+'<br>';    
                });
                html += '</div>'; 
                $('#ajax-error-report').html(html);
            } else {
                $('#reportModal').modal('hide');
                toastr.success('Your report has been sent successfully!');
            }
        }
    };    
    
    $('body').on('click', '.btn-savead', function(e) {
        var request = $.ajax({
            url: base_url+"/save-user-ad",
            type: 'POST',
            dataType: 'json',
            data: {ad: $('#ad_id').val()},
            async: false
        });

        request.done(function(json) {
            if (json.status == 'SUCCESS') {
                $('#btn-save-txt').text(json.text);
            } else {
                toastr.error(json.message);   
            }
        });
    });
    
    
});