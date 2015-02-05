jQuery(function($) {

	var is_msie = (navigator.appVersion.indexOf("MSIE")!=-1) ? true : false;
	var map;

	if ( $('#full-slider').length > 0 ) {
		$('footer').hide();
		$( '.navbar' ).addClass( 'bottom' )
		
		$('#full-slider').maximage({
			cycleOptions: {
				fx: 		'fade',
				speed: 		1000, // Has to match the speed for CSS transitions in jQuery.maximage.css (lines 30 - 33)
				timeout: 	0,
				prev: 		'#slider_prev',
				next: 		'#slider_next',
				pause: 		1,

				before: function(last,current){
					if(!is_msie) {
						// Start HTML5 video when you arrive
						if(jQuery(current).find('video').length > 0) jQuery(current).find('video')[0].play();
					}
				},

				after: function(last,current){
					if(!is_msie) {
						// Pauses HTML5 video when you leave it
						if(jQuery(last).find('video').length > 0) jQuery(last).find('video')[0].pause();
					}
				}
			},

			onFirstImageLoaded: function(){
				jQuery('#cycle-loader').hide();
				jQuery('#full-slider').fadeIn('fast');
			}

		});
	}

	$('#loginForm').on('submit', function(event){
		var $form = $(this);
		var $btn = $('#loginFormSubmit');
		$btn.button('loading');
		$.ajax({
			type:"POST"
			,url:$form.attr('action')
			,data:{
				email: $("#inputEmail").val(),
				password: $.md5($("#inputPassword").val())
			}
			,dataType:"json"
			,success:function(data){
				if(data.success){
					$form[0].reset();
					window.location.replace(data.url);
				}
				else{
					$("#modal-error-msg").html(data.error);
					$('#errorModal').modal('show');
				}
			}
		}).always(function(){
			$btn.button('reset');
		});
 
		event.preventDefault();
	});

	if ( $('#map-canvas').length > 0 ) {

		$("#menu-toggle").click(function(e) {
	        e.preventDefault();
	        $("#wrapper").toggleClass("toggled");
	    });

        google.maps.event.addDomListener(window, 'load', initialize);

	}

});


function initialize() {
    var mapOptions = {
        zoom: 2,
        center: new google.maps.LatLng(35, 0)
    };
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    var styles = [
        /*{
         stylers: [
         { hue: "#00ffe6" },
         { saturation: -20 }
         ]
         },*/{
            "featureType": "administrative.country",
            "elementType": "labels",
            "stylers": [
                {"visibility": "off"}
            ]
        }, {
            "featureType": "administrative.province",
            "stylers": [
                {"visibility": "off"}
            ]
        }, {
            "featureType": "administrative.locality",
            "stylers": [
                {"visibility": "off"}
            ]
        }, {
            "featureType": "administrative.neighborhood",
            "stylers": [
                {"visibility": "off"}
            ]
        }, {
            "featureType": "administrative.land_parcel",
            "stylers": [
                {"visibility": "off"}
            ]
        }/*,{
         "featureType": "poi",
         "stylers": [
         { "visibility": "off" }
         ]
         }*/, {
            "featureType": "road",
            "stylers": [
                {"visibility": "off"}
            ]
        }/*,{
         "featureType": "transit",
         "stylers": [
         { "visibility": "off" }
         ]
         }*/
    ];
    map.setOptions({styles: styles});


    var mapOptionsLoc = {
        lineColor: "#FF0000", // Contorno del pais
        lineWidth: 1, // Tamaño la linea de contorno
        lineOpacity: 1, // Opacidad de la linea de contorno
        backgroundColor: "#FF0000", // Color del fondo
        backgroundOpacity: 0.2 // Opacidad del fondo.
    };
    var mapOptionsAso = {
        lineColor: "#0000FF",
        lineWidth: 1,
        lineOpacity: 1,
        backgroundColor: "#0000FF",
        backgroundOpacity: 0.2
    };
    var mapOptionsPen = {
        lineColor: "#FFA500",
        lineWidth: 1,
        lineOpacity: 1,
        backgroundColor: "#FFA500",
        backgroundOpacity: 0.2
    };

    paintCountry('CO', mapOptionsLoc, '/img/flag_colombia.png', new google.maps.LatLng(0,-85));
    paintCountry('VE', mapOptionsAso, null, null);
    paintCountry('CL', mapOptionsAso, null, null);
    paintCountry('US', mapOptionsAso, null, null);
    paintCountry('MX', mapOptionsPen, '/img/flag_mexico.png', new google.maps.LatLng(19.2600029,-116.5110027,7));
    paintCountry('ES', mapOptionsPen, null, null);
}

function paintCountry(countryCode, countryOptions, countryIcon, countryPosition) {
    
    var countryObj = country[countryCode];
    
    //Area pintada del país
    var polygonObj = new google.maps.Polygon({
        paths: countryObj.coord,
        strokeColor: countryOptions.lineColor,
        strokeOpacity: countryOptions.lineOpacity,
        strokeWeight: countryOptions.lineWidth,
        fillColor: countryOptions.backgroundColor,
        fillOpacity: countryOptions.backgroundOpacity
    });
    polygonObj.setMap(map);
    
    //Icono del pais
    if (countryPosition) {
        var beachMarker = new google.maps.Marker({
            position: countryPosition,
            map: map,
            icon: countryIcon
        });
    }

    google.maps.event.addListener(polygonObj, 'click', function (event) {
        //alert the index of the polygon
        alert("PAIS: " + countryObj.desc);
    });
}
