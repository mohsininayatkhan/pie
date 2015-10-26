$(document).ready(function() {

    
    var form_id = 'frmUserPhoto';
    
    $('#'+form_id+' #upload_img').on('click', function() {
        $('#'+form_id+' #photo').click();
    });
    
    $('body').on('change', '.location', function(e) {
        var location = $(this).attr('id');
        var loc_id = $(this).val();
        var next_location = '';
        var label = '';
        var next_location_id = '';
        var value_field = '';
        
        if (location == 'state') {
            next_location = 'cities';
            next_location_id = 'city';
            label = 'City';
            value_field = 'city_name';
            
            if (loc_id == '') {
                if ($('#user-city').length>0) {
                    $('#user-city').html('');    
                }
                if ($('#user-town').length>0) {
                    $('#user-town').html('');    
                }
                return;
            }
            
        } else if (location == 'city') {
            next_location = 'towns';
            label = 'Town';
            next_location_id = 'town';
            value_field = 'town_name';
            
            if (loc_id == '') {
                if ($('#user-town').length>0) {
                    $('#user-town').html('');    
                }
                return;
            }
        } else {
            return;
        }
        
        var request = $.ajax({
            url: base_url+"/get-locations/"+next_location+"/" + loc_id,
            type: 'GET',
            dataType: 'json'
        });

        request.done(function(json) {
            if (json.status == 'ERROR') {
                return;
            }
            
            if (json.locations.length<=0) {
                $('#user-'+next_location_id).html('');
                return;
            }
            html = '<label for="'+label+'" class="col-lg-2 control-label custom-label">'+label+':</label>'; 
            html += '<div class="col-lg-10">';
            html += '<select name="'+next_location_id+'" class="form-control custom-label location" id="'+next_location_id+'">';
            html += '<option value="0">Select '+label+'</option>';
            $.each(json.locations, function(key, value) {
                html += '<option value="'+value['id']+'">'+value[value_field]+'</option>';
            });
            html += '</select>';
            html += '</div>';
            
            $('#user-'+next_location_id).html(html);
        });
        
    });
    

    $("#frmProfile").validate({
        rules : {
            first_name: {
                required : true
            },
            last_name: {
                required : true
            },
            phone: {
                number : true,
                minlength :11
            },
            website: {
                url: true
            }
        },
        messages: {
            first_name: {
                required: 'Please enter first name.'
            },
            last_name: {
                required : 'Please enter last name.'
            },
            phone: {
                number: 'Please enter valid phone number.',
                minlength: 'Please enter 11 digit valid phone number.'
            },
            website: {
                url: 'Please enter valid URL.'
            }
        }
    });
    
    
    $("#frmChangePassword").validate({
        rules: {
            old_password: {
                required: true
            },
            new_password: {
                minlength: 6,
                required: true
            },
            confirm_password: {
                equalTo: "#new_password"
            }
        },
        messages: {
            old_password: {
                required: 'Please enter password.'
            },
            new_password: {
                required: 'Please enter new password.',
                minlength: 'Please enter atleast 6 characters.'
            },
            confirm_password : {
                equalTo : 'Please enter same password.',
            }
        }
    });
    
    
    // Update Profile
    var profile_options = { 
        target: '#output1',
        beforeSubmit: profileFormRequest,  // pre-submit callback 
        success: profileFormResponse,  // post-submit callback
        type: 'post', 
        dataType: 'json' 
    };
    
    if ($("#frmProfile").validate()) {
       $('#frmProfile').ajaxForm(profile_options);
    }
    
    // Change Password
    var change_pass_options = { 
        target: '#output1',
        beforeSubmit: changePasswordFormRequest,  // pre-submit callback 
        success: changePasswordFormResponse,  // post-submit callback
        type: 'post', 
        dataType: 'json' 
    };
    
    if ($("#frmChangePassword").validate()) {
       $('#frmChangePassword').ajaxForm(change_pass_options);
    }
    
    // Email notification
    var email_notification_options = { 
        target: '#output1',
        beforeSubmit: emailNotificationsFormRequest,  // pre-submit callback 
        success: emailNotificationFormResponse,  // post-submit callback
        type: 'post', 
        dataType: 'json' 
    };
    
    $('#frmNotification').ajaxForm(email_notification_options);
    
    $('body').on('click', '.del-ad', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        bootbox.confirm("Are you sure, you want to delete '"+$(this).attr('title')+"'?", function(result) {
            if (result) {
                var request = $.ajax({
                    url: base_url+"/delete-user-ad",
                    type: 'POST',
                    data:{ad:id},
                    dataType: 'json'
                });

                request.done(function(json) {
                    if (json.status == 'ERROR') {
                        toastr.error(json.message);
                    } else {
                        toastr.success(json.message);
                        window.location.reload(true);
                    }
                });
            }
        }); 
    });
    
    $('body').on('click', '.btn-remove-fav-ad', function(e) {
        var ad_val = $(this).attr('id');
        var unique_id = $(this).attr('name');
        var info = ad_val.split('_');
        ad_val = info[1];
        var request = $.ajax({
            url: base_url+"/save-user-ad",
            type: 'POST',
            dataType: 'json',
            data: {ad: ad_val},
            async: false
        });

        request.done(function(json) {
            if (json.status == 'SUCCESS') {
                $('#rec_'+unique_id).fadeTo("slow", 0.01, function(){
                    $('#rec_'+unique_id).remove();
                    $('#hr_'+unique_id).remove();
                    
                    console.log($('div[id^="rec_"]').length);
                    if ($('div[id^="rec_"]').length<=0) {
                         var not_found  = '<div class="row ads-list"><div class="col-lg-12"><div class="alert alert-info" role="alert" style="text-align: center;">You currently have no ads saved.</div></div></div>';
                         $('.list').html(not_found);
                    }
                });
                
            } else {
                toastr.error(json.message);   
            }
        });
    });
    
    $('.hyperspan').hover(function() {
        $(this).closest('.ads-list').css("background-color", "#f3f8f9");
    });
    
    $('.hyperspan').mouseleave(function() {
        $(this).closest('.ads-list').css("background-color", "");
    });
    
});


function profile_ChangePhoto(photo){
    if (!$(photo).val()) {
        return;
    }
    
    if (typeof photo.files[0] != 'undefined') {
        if (photo.files[0].size > 1000000){
            alert('File size should be less then 1 MB.');
            return;
        }    
    }
    
    ProgressBar.show();
    $.ajax({
        url : 'set-user-profile-photo',
        type : 'POST',
        data: new window.FormData($('#frmUserPhoto')[0]),
        cache: false,
        contentType: false,
        processData: false
        
    }).done(function(json) {
        if (json.status == 'ERROR') {
            ProgressBar.hide();
            toastr.error(json.message);
            return;
        }
        
        if (json.src) {
            $('#user-profil-photo').attr('src', json.src);
            toastr.success(json.message);
        }
        
    }).always(function() {
        ProgressBar.hide();
    });
}

function profileFormRequest(formData, jqForm, options) {
    ProgressBar.show();
    return;
}   

function profileFormResponse(responseText, statusText) {
    
    if (statusText == 'success') {
        if (responseText.status == 'ERROR') {
            toastr.error(responseText.message);
        } else if (responseText.status == 'SUCCESS') {
            toastr.success(responseText.message);
        }
    } else {
        
    }
    ProgressBar.hide();  
}


function changePasswordFormRequest(formData, jqForm, options) {
    ProgressBar.show();
    return;
}   

function changePasswordFormResponse(responseText, statusText) {
    
    if (statusText == 'success') {
        if (responseText.status == 'ERROR') {
            toastr.error(responseText.message);
        } else if (responseText.status == 'SUCCESS') {
            toastr.success(responseText.message);
        }
    } else {
        
    }
    ProgressBar.hide();  
}


function emailNotificationsFormRequest(formData, jqForm, options) {
    ProgressBar.show();
    return;
}   

function emailNotificationFormResponse(responseText, statusText) {
    
    if (statusText == 'success') {
        if (responseText.status == 'ERROR') {
            toastr.error(responseText.message);
        } else if (responseText.status == 'SUCCESS') {
            toastr.success(responseText.message);
        }
    } else {
        
    }
    ProgressBar.hide();  
}