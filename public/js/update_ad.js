$(document).ready(function() {
    
    var options = { 
        target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  formRequest,  // pre-submit callback 
        success:       formResponse,  // post-submit callback
        type: 'post', 
        dataType:  'json' 
    };
    
    function formRequest(formData, jqForm, options) {
        var queryString = $.param(formData);
        ProgressBar.show();
        return;
    }
    
    function formResponse(responseText, statusText) {
        
        if (statusText == 'success') {
            if (responseText.status == 'ERROR') {
                html = '<div role="alert" class="alert alert-danger">';
                responseText.messages.forEach(function(message) {
                    html += message+'<br>';    
                });
                html += '</div>'; 
                $('#ajax-error-messages').html(html);
                
                $('html, body').animate({
                    scrollTop: $("body").offset().top
                }, 1000); 
                   
            } else if (responseText.status == 'SUCCESS') {
                $('#ajax-error-messages').html('');
                window.location.href = base_url+"/user-ads/"+responseText.user;
                toastr.success('Ad updated successfully!');    
            }
        } else {
            
        }
        ProgressBar.hide();  
    }
    
    // form validation
    $("#frmUpdateAd").validate({
        rules: {
            title: {
                required: true,
                minlength: 20,
                maxlength: 80
            }, 
            description: {
                required: true,
                minlength: 50
            },
            contact_name: {
               required: true, 
            },
            contact_email: {
                required: true,
                email: true
            },
            contact_phone: {
                required: true,
                number: true,
                minlength :11    
            },
            link: {
            	url: true	
            }
        },
        messages: {
            title: {
                required: 'Please enter title.',
                minlength: 'Please enter atleast 20 characters.',
                maxlength: 'Please enter no more than 80 characters.'
            },
            description: {
                required: 'Please enter description.',
                minlength: 'Please enter atleast 50 characters.'
            },
            contact_name: {
                required: 'Please enter name.'
            },
            contact_email: {
                required : 'Please enter email.',
                email : 'Please enter valid email.'
            },
            contact_phone: {
                required : 'Please enter phone.',
                number : 'Please enter valid phone number.',
                minlength: 'Please enter 11 digit valid phone number'
            },
            link: {
            	url: 'Please enter valid link'
            }           
        }
    });
    
    setAttributesValidationRules();
    if ($('#price').length) {
         $('#price').rules('add', {
             required: true,
             number: true,
             messages: {
                 required: 'Please enter price.',
                number: 'Please enter valid price.'
            }
        });
    }
    $("#frmUpdateAd").validate();    
    //setAttributesValidationRules();
    
    // bind form using 'ajaxForm'
    if ($("#frmUpdateAd").validate()) {
       $('#frmUpdateAd').ajaxForm(options);
    }    
    
});

function validateForm() {
    return true;
}