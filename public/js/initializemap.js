function initializeMap(doc_lat,doc_lng,img_pointer,text) {
		//alert(img_pointer);
		//alert(doc_lat+","+doc_lng);
        var myOptions = {
          center: new google.maps.LatLng(doc_lat,doc_lng),
          zoom: 17,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);
			
		var marker = new google.maps.Marker({
          position: map.getCenter(),
          map: map,
          icon: img_pointer,
          title: 'Click to zoom'
        });
		var message = text;
		var infowindow = new google.maps.InfoWindow({
			  content: message
			});
	     google.maps.event.addListener(marker, 'click', function() {
		  infowindow.open(marker.get('map'), marker);
          
        });
}


function initializeGoogleMap(doc_id,doc_lat,doc_lng,img_pointer,url_map) {
	//alert(url_map);
	//alert(img_pointer);
	//alert(doc_lat+","+doc_lng);
	var myOptions = {
		      zoom: 13,
		      center: new google.maps.LatLng(11.558831,104.917445),
		      mapTypeId: google.maps.MapTypeId.ROADMAP
		    };
	
	var first_click = true;
	
	var map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
	
	if(doc_lat !== '' && doc_lng !== ''){		 
		  var latlng = new google.maps.LatLng(parseFloat(doc_lat), parseFloat(doc_lng));		  
		  placeMarker(latlng, map, false, img_pointer, url_map, doc_id);
    }
	else{
    	 google.maps.event.addListener(map, 'click', function(e) {
    		 if(first_click){
		   	      var zoomlevel = map.getZoom();		   	      
		   	      if(zoomlevel >= 17){
		   	    	  placeMarker(e.latLng, map, true, img_pointer, url_map, doc_id);
		   	    	  first_click = false;
		   	      }
		   	      else{
		   	    	  alert('Please zoom in to make sure, you select the rigth place.');
		   	      }	   	      
    		 }
       });    
	}
	
    var input = document.getElementById('searchTextField');
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    google.maps.event.addListener(autocomplete, 'place_changed', function() {      
      var place = autocomplete.getPlace();
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);  // Why 17? Because it looks good.
      }    
    });    

    //Sets a listener on a radio button to change the filter type on Places
	// Autocomplete.
	function setupClickListener(id, types) {
	   var radioButton = document.getElementById(id);
	   google.maps.event.addDomListener(radioButton, 'click', function() {
	     autocomplete.setTypes(types);
	   });
	}

    setupClickListener('changetype-all', []);
    setupClickListener('changetype-establishment', ['establishment']);
    setupClickListener('changetype-geocode', ['geocode']);
  }


function placeMarker(position, map, save, img_pointer, url_map, doc_id) {	
	if(save){
		updateMaptoDatabase(position.lat(), position.lng(), url_map, doc_id);
	}
    var marker = new google.maps.Marker({
      position: position,
      map: map,
      icon: img_pointer,
      draggable: true
    });
    
    google.maps.event.addListener(marker, 'dragend', function(e){    	
    	updateMaptoDatabase(e.latLng.lat(), e.latLng.lng(), url_map, doc_id);
   });
    
    map.panTo(position);    
    
}

function updateMaptoDatabase(_lat, _lng, url_map, doc_id){	
	//alert(url_map);
	//alert(_lat+","+_lng);
    $.ajax({
        type: 'POST',
		url : url_map,
		data:{
			id : doc_id,
			lat: _lat,
			lng: _lng
		},
		dataType: "json"

	});
    
   /* $.post(url_map, { id: doc_id, lat: _lat, lng: _lng},
		   function(data) {
		     alert("Data Loaded: " + data);
		   });*/
}