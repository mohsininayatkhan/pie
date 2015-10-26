//
$(document).ready(function() {
	$('#spotlight-ads, #event-ads').carousel ({
	    interval:5000
	});
   
   	$('#location-sel').ddslick({
   		onSelected: function(selectedData) {
   		//callback function: do something with selectedData;
        }   
   	});
   
   	$('#htmlselect').ddslick({
   		onSelected: function(selectedData) {
       		//callback function: do something with selectedData;
        }   
   	});
   
   	$('#location > ul').toggleClass('no-js js');
   	$('#location .js ul').hide();
   
   	$('#location .js').click(function(e) {
   	$('#location .js ul').slideToggle(200);
   	$('#location .clicker').toggleClass('active');
    	e.stopPropagation();
   	});
   
   	$(document).click(function() {
    	if ($('#location .js ul').is(':visible')) {
   			$('#location .js ul', this).slideUp();
   			$('#location .clicker').removeClass('active');
       }
   	});
   
   	if ($('#state').val()!='') {
   		showLocations(2,'state');
   		selectedLoc(2, 'state');
   	}
   
   	if ($('#city').val()!='') {
   		selectedLoc(56, 'city');
   	}   
   
   	$('#location ul li ul').hide();
   	
   	var request = $.ajax({
    	url: base_url+"/autocomplete",
        type: 'GET',
        dataType: 'json'
    });
    request.done(function(data) {
        var $input = $('.typeahead');
		$input.typeahead({source:data.options, autoSelect: true});   
    });   
   
    
});

function showCats(id, opt) {

     var request = $.ajax({
         url: base_url+"/ajax/category/" + id + "/" + opt,
         type: 'GET',
         dataType: 'html'
     });
    request.done(function(data) {
        $('#categories').html(data);   
    });
    
    if(opt=='main') {
        $('#category').val('');
    }        
}

function hideSelect(){

    if ($('#categories ul li ul').is(':visible')) {

        $('#categories ul li ul').slideUp();
        $('#categories ul li .clicker').removeClass('active');
        
    }else{

        $('#categories ul li ul').slideDown();
        $('#categories ul li .clicker').addClass('active'); 
    }
}

function selectedText(id){
    
    $('#categories .glyphicon').removeClass('glyphicon-ok');
    $('#selected_'+id+' .glyphicon').addClass('glyphicon-ok');
    var html = $('#selected_'+id).html();
    $('#category').val(id);
    $('#categories ul li .clicker').html(html); 
    hideSelect();
}

function selectedLoc(id, type){
    $('#location .glyphicon').removeClass('glyphicon-ok');
    $('#city_'+id+' .glyphicon').addClass('glyphicon-ok');
    $('#city_'+id+' .glyphicon').addClass('city-ok');
    if(type=='state'){
        var html = $('#wholestate').html();
        $('#state').val(id);
    }else{
        var html = $('#city_'+id).html();
        var state = $('#city_'+id).attr('data-state');
        $('#state').val(state);
        $('#city').val(id);
    }
    $('#location_id').val(id);
    $('#loc_type').val(type);
    $('#location ul li .clicker').html(html);
    $('#location ul li .clicker .glyphicon').removeClass('red-nav').removeClass('glyphicon-minus').addClass('glyphicon-ok'); 
    hideLocation();    
}

function hideLocation(){
    
    if ($('#location ul li ul').is(':visible')) {
        $('#location ul li ul').slideUp();
        $('#location ul li .clicker').removeClass('active');
    } else {
        $('#location ul li ul').slideDown();
        $('#location ul li .clicker').addClass('active'); 
    }
}

function showLocations(id, opt){
     
     var request = $.ajax({
         url: base_url+"/ajax/location/" + id + "/" + opt,
         type: 'GET',
         dataType: 'html',
         async: false
     });
    request.done(function(data) {
        if(opt=='minus') {
            $('#state').val('');
            $('#city').val('');
        }
        $('#location').html(data);
    });     
}