$(document).ready(function() {
    
    var on_hover_follow = false;
    $('body').on('click', '.follow-btn', function(e) {
        
        var user = $(this).attr('id');
        var request = $.ajax({
            url: base_url+"/follow-user/" + user,
            type: 'GET',
            async: false,
            dataType: 'json'
        });

        request.done(function(json) {

            if (json.status == 'ERROR') {
                bootbox.alert(json.message);
                return;
            }
            
            $('#'+user).removeClass('btn-danger');
            $('#'+user).addClass('btn-primary');
            if (json.message=='Follow') {
                $('#'+user).removeClass('btn-following');
            } else {
                $('#'+user).addClass('btn-following');
            }
            on_hover_follow = true;
            $('#'+user).html(json.message);
            $('#my-following-count').text(json.followers);
            
            /*if (json.followers<=0) {
                $('#'+user+'-followers-count').html('');
            } else {
                $('#'+user+'-followers-count').html('<span class="fa fa-1x fa-rss"></span> Followed by <a data-toggle="modal" data-target="#followersModal" id="view-followers" href="javascript:void(0)">'+json.followers+' people</a>');
            } */   
        });
        
    });
    
    $('body').on('mouseenter', '.btn-following', function(e) {
        $(this).removeClass('btn-primary').addClass('btn-danger').html('Unfollow');
    });
    
    $('body').on('mouseleave', '.btn-following', function(e) {
        if ($(this).html() != 'Follow') {
            $(this).removeClass('btn-danger').addClass('btn-primary').html('Following');
         }
    });    
});
