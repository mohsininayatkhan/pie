/* Image uploading on create ad form
*
*/

//var upload_img_count = 0;
//var upload_img_limit = 6;
//var upload_file_count = 0;

setting = {
    upload_img_count : 0,
    upload_img_limit : 6,
    upload_file_count : 0,
    img_name : ''
};

$(document).ready(function() {
    
    var form_id = $('#upload_img').closest('form').attr('id');
    //var form_id = $('form').attr('id');
    
    if ($('#adimages_count').length) {
        setting.upload_img_count = $('#adimages_count').val(); 
    } 
    
    $('#'+form_id+' #upload_img').on('click', function() {
        $('#'+form_id).append('<input class="attachment file-' + (setting.upload_file_count++) + '" id="attachment-' + setting.upload_file_count + '" name="file[]" type="file" multiple="" />');
        $('#'+form_id+' #attachment-' + setting.upload_file_count).click();
        $('#'+form_id+' #attachment-' + setting.upload_file_count).css('visibility', 'hidden');
    });

    $('body').on('change', '#'+form_id+' .attachment', function() {

        var files = this.files;
        var attachment_html = '';

        for ( i = 0; i < files.length; i++) {

            setting.img_name = files[i].name;
            //console.log(setting.img_name);
            var reader = new FileReader();
            
            currFile = files[i];
            
            reader.onload = (function(theFile){
                var fileName = theFile.name;
                return function(e){
                    var file_extention = e.target.result.split(",")[0].split(":")[1].split(";")[0].split("/")[1];
                    if (file_extention == 'png' || file_extention == 'jpeg' || file_extention == 'jpg' || file_extention == 'gif') {
    
                        if (e.target.readyState == FileReader.DONE) {
                            if (setting.upload_img_count >= setting.upload_img_limit) {
                                $('#upload_img').hide();
                                return;
                            } else {
                                setting.upload_img_count++;
                            }
    
                            attachment_html = '<div class="col-xs-2 col-md-2" id="file-' + setting.upload_img_count + '" >';
                            attachment_html += '<div class="img-thumbs">';
                            attachment_html += '<img class="img-responsive" src="' + e.target.result + '"/>';
                            attachment_html += '<div class="clearfix"></div>';
                            attachment_html += '<input type="hidden" name="img_names[]" value="'+fileName+'"></div>';
                            attachment_html += '<button onclick="removeImage(\'#file-' + setting.upload_img_count + '\');" type="button" class="btn btn-sm btn-danger img-remove-btn"><i class="glyphicon glyphicon-remove"></i> Remove</button>';
                            attachment_html += '</div></div>';
                            //attachment_html = '<li class="file-' + upload_img_count + ' media-list-box">' + '<img style="height:140px; width:140px" src="' + e.target.result + '"/>' + '<a class="cursor-pointer" onclick="removeImage(\'.file-' + upload_img_count + '\');">x</a>' + '</li>';
                            $('#'+form_id+' .images-section').append(attachment_html);
                        }
                    }
                };
            })(currFile);  
            reader.readAsDataURL(files[i]);
        }
        if (setting.upload_img_count >= setting.upload_img_limit - 1) {
            $('#upload_img').hide();
        }
    });

});

function removeImage(img)
{
    
    $(img).remove();
    setting.upload_img_count--;

    if (setting.upload_img_count <= setting.upload_img_limit - 1) {
        $('#upload_img').show();
    }
}

function removeExistingImage(num, img)
{
    var request = $.ajax({
        url: base_url+"/deleteadimg",
        type: 'GET',
        data: {'img': img, 'ad':$('#ad').val()},
        dataType: 'json'
    });
    
    request.done(function(json) {
        if (json.status == 'ERROR') {
            toastr.error(json.message);
            return;
        }
        removeImage('#file-'+num);
    });
    
    request.fail(function() {
        return false;
    });

    request.always(function() {
    });
    return;
}
