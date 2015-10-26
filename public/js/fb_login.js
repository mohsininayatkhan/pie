// Load the SDK Asynchronously
(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

window.fbAsyncInit = function() {
    
    FB.init({
        appId      : 802593713157203, 
        status     : true, 
        cookie     : true, 
        xfbml      : true, 
        frictionlessRequests : true
    });
    
    $(document).on('click', '#fb-login', function(e){
        FB.getLoginStatus(function(response) {
            if (response.authResponse) {
                fb_login(response.authResponse.accessToken);
            } else {
                FB.login(function(response) {
                    if (response.authResponse) {
                        fb_login(response.authResponse.accessToken);
                    }
                }, {
                    scope: 'email,user_location'
                });
            }
        });
    });
};

function fb_login(token) {
    ProgressBar.show();
    var request = $.ajax({
        url: base_url+'/facebook-login',
        type: 'POST',
        dataType: 'json',
        data: {'token': token}
    });
    
    request.done(function(json) {
        ProgressBar.hide();
        if (json.status=='ERROR') {
            toastr.error(json.message);
            return; 
        }
        
        if (json.action=='login') {
            if (json.redirect_url!='') {
                window.location.href = json.redirect_url;
            } else {
                window.location.href = base_url+"/manage-ads";    
            }
        } else if (json.action=='registered') {
            window.location.href = base_url+"/my-account";
        }
    });  
}