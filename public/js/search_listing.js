$(document).ready(function() {
    
    if ($('#category').val() != '' && $('#category').val() != '0' ) {
        getAttributes();
        askPrice();
    }
    
    var request = $.ajax({
    	url: base_url+"/autocomplete",
        type: 'GET',
        dataType: 'json'
    });
    request.done(function(data) {
        var $input = $('.typeahead');
		$input.typeahead({source:data.options, autoSelect: true});   
    });     
    
    /*
    $('body').on('change', '.location', function(e) {
        
        var location = $(this).attr('id');
        if (location == 'town') return;
        
        var next_loc = 'cities';
        
        if (location=='city') {
            var next_loc = 'towns';
        }
        
        if (location == 'state') {
            if ($(this).val() == '') {
                $('#city').empty();
                $('#div-cities').removeClass('show');
                $('#div-cities').addClass('hide');
                
                $('#town').empty();
                $('#div-towns').removeClass('show');
                $('#div-towns').addClass('hide');
                return;
            }
            $('#town').empty();
            $('#div-towns').removeClass('show');
            $('#div-towns').addClass('hide');
        }
        
        var val = 0;
        if ($(this).val()!='') {
            val = $(this).val();
        }
        var request = $.ajax({
            url : base_url+"/get-locations/"+next_loc+"/" + val,
            type : 'GET',
            dataType : 'json'
        });
        
        request.done(function(json) {

            if (next_loc == 'cities') {
                
                $('#city').empty();
                                
                if (json.locations.length>0) {
                    
                    $('#city').append($('<option>', { 
                        value: '',
                        text : 'All Cities'
                    }));
                
                    $.each(json.locations, function (i, item) {
                        $('#city').append($('<option>', { 
                            value: item.id,
                            text : item.city_name
                        }));
                    });
                
                    $('#div-cities').removeClass('hide');
                    $('#div-cities').addClass('show');
                } else {
                    $('#div-cities').removeClass('show');
                    $('#div-cities').addClass('hide');
                }
            } else if (next_loc == 'towns') {
                
                $('#town').empty();
                
                if (json.locations.length>0) {
                    
                    $('#town').append($('<option>', { 
                        value: '',
                        text : 'All Towns'
                    }));
                
                    $.each(json.locations, function (i, item) {
                        $('#town').append($('<option>', { 
                            value: item.id,
                            text : item.town_name
                        }));
                    });
                    
                    $('#div-towns').removeClass('hide');
                    $('#div-towns').addClass('show');
                } else {
                    $('#div-towns').removeClass('show');
                    $('#div-towns').addClass('hide');
                }
            }
        });
        
        request.fail(function() {
            return false;
        });

        request.always(function() {
        });
    });*/
    
     $('body').on('click', '#search-listing-btn', function(e) {
        $('#frmRefineSearch').submit();
    });

    
    $('body').on('change', '#sortby', function(e) {
        $('#frmRefineSearch #sort').val($(this).val());
        $('#frmRefineSearch').submit();
    });
     
    $('.hyperspan').hover(function() {
        $(this).closest('.ads-list').css("background-color", "#f3f8f9");
    });
    
    $('.hyperspan').mouseleave(function() {
        $(this).closest('.ads-list').css("background-color", "");
    });
    
});



function getAttributes() {
    var request = $.ajax({
        url : base_url+"/search/attributes/" + $('#category').val(),
        type : 'GET',
        dataType : 'html'
    });
    
    request.done(function(html) {
        $('#search-attributes').html(html);
        var qs = QueryStringToJSON(location.search.substring(1));
        $.each(qs, function(key, value) {
            if (key.indexOf('attr_') != -1) {
                key = key.replace("%28", "(");
                key = key.replace("%29", ")");
                
                key = key.replace("(", "\\(");
                key = key.replace(")", "\\)");

                // for checboxes
                if (value instanceof Array) {
                   $.each(value, function(k, v) {
                       var s = key.replace('%5B%5D', '');
                       var s = s.replace('[]', '');
                       var info = v.split('_');
                       $('#'+s+'_'+info[1]).prop( "checked", true);
                   });
                } else {
                    if (key.indexOf('%5B%5D')== -1) {
                        key = key.replace(/\%5B(.+?)\%5D/g, '');
                        var cinfo = value.split('_');
                        // for radio button
                        if ($('input[name='+key+']').is(':radio')) {
                             var info = value.split('_');
                             $('#'+key+'_'+info[1]).attr('checked', true);
                        } else if ($('input[id='+key+'_'+cinfo[1]+']').is(':checkbox')) {
                            $('#'+key+'_'+cinfo[1]).prop( "checked", true);
                        } else {
                            $('#'+key).val(value);
                        }
                          
                    } else {
                        var s = key.replace('%5B%5D', '');
                        var info = value.split('_');
                        $('#'+s+'_'+info[1]).attr('checked', true);
                    }
                }     
            } else {
                if (key=='min_price') {
                    $('#'+key).val(value);
                }
                
                if (key=='max_price') {
                    $('#'+key).val(value);
                }
            }
        });
    });
        
    request.fail(function() {
        return false;
    });

    request.always(function() {
    });
}


function askPrice() {

    var request = $.ajax({
        url : base_url+"/get-category/" + $('#category').val(),
        type : 'GET',
        dataType : 'json'
    });

    request.done(function(json) {
        if (json.status != 'ERROR') {
            if (json.category.length>0 && json.category[0].ask_for_price == 1) {
                var html = '<div class="form-group"><hr></div>';   
                html += '<h5 class="attribute-heading">Price:</h5>';
                html += '<div class="row price-boxes" >';
                html += '<div class="col-sm-6">';
                html += '<input type="text" name="min_price" id="min_price" class="form-control" placeholder="Min">';
                html += '</div>';
                html += '<div class="col-sm-6">';
                html += '<input type="text" name="max_price" id="max_price" class="form-control" placeholder="Max">';
                html += '</div>';
                html += '</div>';
                $('#ask-for-price').html(html);
            } else {
                $('#ask-for-price').html('');
            }
                
        }
    });
}

function QueryStringToJSON(str) {
    var pairs = str.split('&');
    var result = {};
    pairs.forEach(function(pair) {
        pair = pair.split('=');
        var name = pair[0]
        var value = pair[1]
        if( name.length )
            if (result[name] !== undefined) {
                if (!result[name].push) {
                    result[name] = [result[name]];
                }
            result[name].push(value || '');
            } else {
                result[name] = value || '';
            }
    });
    return( result );
}

//var query_string = QueryStringToJSON();

function getQueryStrings() { 
    var assoc  = {};
    var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
    var queryString = location.search.substring(1); 
    return queryString;
    var keyValues = queryString.split('&');
    //console.log(keyValues); 

    for(var i in keyValues) { 
        var key = keyValues[i].split('=');
        if (key.length > 1) {
            assoc[decode(key[0])] = decode(key[1]);
        }
    } 
  return assoc; 
}

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}
