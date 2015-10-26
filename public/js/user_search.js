$(document).ready(function() {

    $('body').on('click', '.follow-btn', function(e) {
        
        var user = $(this).attr('id');
        var request = $.ajax({
            url: base_url+"/follow-user/" + user,
            type: 'GET',
            dataType: 'json'
        });

        request.done(function(json) {

            if (json.status == 'ERROR') {
                bootbox.alert(json.message);
                return;
            }
            $('#'+user).html('<span class="fa fa-1x fa-rss"></span> '+json.message);
            
            if (json.followers<=0) {
                $('#'+user+'-followers-count').html('');
            } else {
                $('#'+user+'-followers-count').html('<span class="fa fa-1x fa-rss"></span> '+json.followers+' Followers');
            }    
        });
        
    });
    
});
