$(document).ready(function() {

    $("input[name=type]").change(function() {
        if (this.checked) {
            var val = $(this).val();
            if (val == 'new') {
                $("#pwd").val('');
            } 
        } 
    });
    
    $('body').on('click', '#follow-btn', function(e) {
        console.log('test');
    });
    
    $("#frmRegister").validate({
        rules : {
            first_name : {
                required : true
            },
            last_name : {
                required : true
            },
            email : {
                email : true,
                required : true,
                remote: {
                    url: base_url+"/check-email",
                    type: "post"
                }
            },
            phone : {
                number : true,
                minlength :11
            },
            password : {
                minlength : 6,
                required : true
            },
            confirm_password : {
                equalTo : "#password"
            }
        },
        messages : {
            first_name : {
                required : 'Please enter first name.'
            },
            last_name : {
                required : 'Please enter last name.'
            },
            email : {
                required : 'Please enter email.',
                email    : 'Please enter valid email.',
                remote   : $.validator.format("Email already in use. <a href='"+base_url+"/login'>Sign In</a>")
            },
            phone : {
                number      : 'Please enter valid phone number.',
                minlength   : 'Please enter 11 digit valid phone number.'
            },
            password : {
                required   : 'Please enter password.',
                minlength  : 'Please enter at least 6 characters password'
            },
            confirm_password : {
                equalTo : 'Please enter same password.',
            }
        }
    });
    
});
