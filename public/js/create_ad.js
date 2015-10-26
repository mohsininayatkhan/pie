$(document).ready(function() {
    
    var options = { 
        target:        '#output1',   // target element(s) to be updated with server response 
        beforeSubmit:  formRequest,  // pre-submit callback 
        success:       formResponse,  // post-submit callback
        type: 'post', 
        dataType:  'json'  
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 
 
   
    
    $('body').on('click', '.locations-selection .list-group .list-group-item', function(e) {

        e.preventDefault();
        
        var total_levels = 3;
        div = $(this).closest('div[id^="level-"]');
        ce = $(this);
        var id = div.attr('id');

        if (ce.hasClass('active') || ce.hasClass('list-final')) {
            return;
        }

        $('.locations-selection #' + id + ' a, #' + id + ' a *').removeClass('active');
        $('.locations-selection #' + id + ' a, #' + id + ' a *').removeClass('list-final');

        var loc = $(this).attr('id');
        var level_info = div.attr('id').split('-');
        var current_level = parseInt(level_info[1]);
        var loc_level;

        if (current_level == 1) {
            loc_level = 'cities';
            $('#state').val(loc);
            $('#city').val('');
            $('#town').val('');
        } else if (current_level == 2) {
            loc_level = 'towns';
            $('#city').val(loc);
            $('#town').val('');
        } else if (current_level == 3) {
            ce.addClass('list-final');
            $('#loc-error').html('');
            $('#town').val(loc);
            return;
        }

        var request = $.ajax({
            url: base_url+"/get-locations/" + loc_level + "/" + loc,
            type: 'GET',
            dataType: 'json'
        });

        request.done(function(json) {

            if (json.status == 'ERROR') {
                return;
            }

            if (!json.locations.length) {
                ce.addClass('list-final');
                $('#loc-error').html('');
            } else {
                ce.addClass('active');
            }

            var html = '';
            $.each(json.locations, function(key, value) {
                html += '<a href="#" id=' + value['id'] + ' class="cat-list-item list-group-item">';
                if (loc_level == 'cities') {
                    html += value['city_name'] + '</a>';
                } else if (loc_level == 'towns') {
                    html += value['town_name'] + '</a>';
                }
            });

            next_level = parseInt(current_level + 1);
            $('.locations-selection #level-' + next_level).html(html);

            for ( i = next_level + 1; i <= total_levels; i++) {
                $('.locations-selection #level-' + i).html('');
            }
        });

    });

    $('body').on('click', '.categories-selection .list-group .list-group-item', function(e) {

        e.preventDefault();

        var total_levels = 4;
        div = $(this).closest('div[id^="level-"]');
        ce = $(this);
        var id = div.attr('id');

        $('#cat').val(cat);
        //$('#categories').val(cat);

        if (ce.hasClass('active') || ce.hasClass('list-final')) {
            return;
        }

        $('#cat-specs-section').removeClass('show');
        $('#cat-specs-section').addClass('hidden');
        $('#cat-specs').html('');

        $('.categories-selection #' + id + ' a, #' + id + ' a *').removeClass('active');
        $('.categories-selection #' + id + ' a, #' + id + ' a *').removeClass('list-final');

        var cat = $(this).attr('id');

        var request = $.ajax({
            url: base_url+"/get-child-categories/" + cat,
            type: 'GET',
            dataType: 'json'
        });

        request.done(function(json) {
            if (json.status == 'ERROR') {
                return;
            }

            var level_info = div.attr('id').split('-');
            var current_level = parseInt(level_info[1]);

            var html = '';

            if (!json.categories.length) {
                ce.addClass('list-final');
                $('#cat').val(cat);
                $('#cat-error').html('');

                var selected_categories = [];
                $('.categories-selection .active, .categories-selection.list-final').each(function() {
                    selected_categories.push(this.id);
                });
                
                selected_categories.push($('#cat').val());
                $('#categories').val(selected_categories.join());
                getCategoryAttibutes(selected_categories.join());

            } else {
                ce.addClass('active');
            }

            $.each(json.categories, function(key, value) {
                html += '<a href="#" id=' + value['id'] + ' class="cat-list-item list-group-item">' + value['name'] + '</a>';
            });

            next_level = parseInt(current_level + 1);
            $('.categories-selection #level-' + next_level).html(html);

            for ( i = next_level + 1; i <= total_levels; i++) {
                $('.categories-selection #level-' + i).html('');
            }
        });

        request.fail(function() {
            return false;
        });

        request.always(function() {
        });
    });

    // form validation
    $("#frmCreateAd").validate({
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
            link: {
            	url: 'Please enter valid link'
            }
        }
    });
    
    // bind form using 'ajaxForm' 
    if ($("#frmCreateAd").validate()) {
       $('#frmCreateAd').ajaxForm(options);
    }    
    
    function formRequest(formData, jqForm, options) {
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
                if (responseText.mode == 1) {
                    window.location.href = base_url+"/thankyou/"+responseText.code_2;    
                } else  {
                    window.location.href = base_url+"/new-account-greeting/"+responseText.code_1; 
                }
            }
        } else {
            
        }
        ProgressBar.hide();  
    }
    
    $("#frmCreateAd").validate();
    
    // validation rules for create new account
    if ($('#create_user').val() == 'yes') {

        $('#first_name').rules('add', {
            required: true,
            messages: {
                required: 'Please enter first name.'
            }
        });

        $('#last_name').rules('add', {
            required: true,
            messages: {
                required: 'Please enter last name.'
            }
        });

        $('#email').rules('add', {
            email: true,
            required: true,
            remote: {
                url: base_url+"/check-email",
                type: "post"
            },
            messages: {
                required: 'Please enter email.',
                email: 'Please enter valid email.',
                remote: $.validator.format("Email already in use. <a href='/login'>Sign In</a>")
            }
        });

        $("#phone").rules("add", {
            required: true,
            number: true,
            minlength: 11,
            messages: {
                required: 'Please enter phone.',
                number: 'Please enter valid phone number.',
                minlength: 'Please enter 11 digit valid phone number.'
            }
        });
        
        $('#password').rules('add', {
            minlength: 6,
            required: true,
            messages: {
                required: 'Please enter password.',
                minlength: 'Password must be consist of 6 characters atleast.',
            }
        });
        
        $('#confirm_password').rules('add', {
            equalTo: "#password"
        });
    } else {
        
         $('#contact_name').rules('add', {
            required: true,
            messages: {
                required: 'Please enter name.'
            }
        });

        $('#contact_email').rules('add', {
            required: true,
            email: true,
            messages: {
                required: 'Please enter email.',
                email: 'Please enter valid email.'
            }
        });
        
        $("#contact_phone").rules("add", {
            required: true,
            number: true,
            minlength: 11,
            messages: {
                required: 'Please enter phone.',
                number: 'Please enter valid phone number.',
                minlength: 'Please enter 11 digit valid phone number'
            }
        });
    }
});


function getCategory(cat) {

    var request = $.ajax({
        url: base_url+"/get-category/" + cat,
        type: 'GET',
        dataType: 'json'
    });

    request.done(function(json) {
        if (json.status != 'ERROR') {
            $("#frmCreateAd").validate();
            
            if ($('#price').length ) {
                $('#price').rules('remove');
            }
            
            if (json.category[0].ask_for_price == 1) {
                
                var price_html = '<div class="form-group">';
                price_html += '<label for="Price" class="col-sm-2 control-label custom-label">Price:*</label>';
                price_html += '<div class="col-sm-8">';
                price_html += '<input type="text" class="form-control" id="price" name="price">';
                price_html += '</div>';
                price_html += '<div class="col-sm-2">';
                price_html += '<div class="checkbox">';
                price_html += '<input type="checkbox" id="price_negotiable" name="price_negotiable" value="1">';
                price_html += '<label for="price_negotiable">Negotiable?</label>';
                price_html += '</div>';
                price_html += '</div>';
                price_html += '</div>';
                
                $('#ad-price').html(price_html);
                
                $('#price').rules('add', {
                    required: true,
                    number: true,
                    messages: {
                        required: 'Please enter price.',
                        number: 'Please enter valid price.'
                    }
                });
                
            } else {
                $('#ad-price').html('');
            }

            if (json.category[0].ask_for_images == 1) {
                $('#ad-images').addClass('show');
                $('#ad-images').removeClass('hidden');
            } else {
                $('#ad-images').addClass('hidden');
                $('#ad-images').removeClass('show');
            }

        }
    });
}

function getCategoryAttibutes(cat) {

    var request = $.ajax({
        url: base_url+"/ad/attributes/" + cat,
        type: 'GET',
        dataType: 'html'
    });

    request.done(function(html) {
        if (html != '') {
            $('#cat-specs-section').removeClass('hidden');
            $('#cat-specs-section').addClass('show');
            $('#cat-specs').html(html);
            
            $("#frmCreateAd").validate();    
            setAttributesValidationRules();
        } else {
            $('#cat-specs-section').removeClass('show');
            $('#cat-specs-section').addClass('hidden');
            $('#cat-specs').html(html);
        }

        getCategory($('#cat').val());

    });

    request.fail(function() {
        return false;
    });

    request.always(function() {
    });
}

function validateForm() {
    
    var flag = true;
    var cat_flag = true;
    var loc_flag = true;
    
    if ($('#cat').val() == '') {
        $('#cat-error').html('<p class="error-message">Please select last level category.</p>');
        flag = false;
        cat_flag = false;
    } else {
        $('#cat-error').html('');
        cat_flag=true;
    }
    
    if (!$('.locations-selection .list-group-item').hasClass('list-final')) {
        $('#loc-error').html('<p class="error-message">Please select last level location.</p>');
        flag = false;
        loc_flag = false;
    } else {
        $('#loc-error').html('');
        loc_flag = true;
    }
    
    if (!cat_flag) {
        $('html, body').animate({
            scrollTop: $("#cat-error").offset().top
        }, 1000);    
    } else if (!loc_flag) {
        $('html, body').animate({
            scrollTop: $("#loc-error").offset().top
        }, 1000);
    }
    return flag;
}