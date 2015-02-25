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
var mapOptions = {
    zoom: 2,
    center: new google.maps.LatLng(35, 0)
};

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

		var msProduct = $('#ms-filter-product').magicSuggest({
            data: 'posicion/listInAgreement'
            ,resultsField: 'data'
            ,placeholder: 'Select...'
            //,mode: 'remote'
            ,valueField: 'id_posicion'
            ,displayField: 'posicion'
            ,allowFreeEntries: false
            //,highlight:false
            ,useZebraStyle: true
            ,maxSelection: 1
            ,typeDelay: 600
            //,minChars:2
            ,selectionPosition: 'bottom'
            ,selectionStacked: true
            ,selectionRenderer: function(data){
                //console.log(data);
                return data.posicion + ' (<b>' + data.id_posicion + '</b>)';
            }
        });
        var msCountry = $('#ms-filter-country').magicSuggest({
            data: 'pais/listInAgreement'
            ,resultsField: 'data'
            ,placeholder: 'Select...'
            //,mode: 'remote'
            ,valueField: 'id_pais'
            ,displayField: 'pais'
            ,allowFreeEntries: false
            //,highlight:false
            ,useZebraStyle: true
            ,maxSelection: 5
            ,typeDelay: 600
            //,minChars:2
            ,selectionPosition: 'bottom'
            ,selectionStacked: true
            ,selectionRenderer: function(data){
                //console.log(data);
                return data.pais + ' (<b>' + data.pais_iata + '</b>)';
            }
        });

       /* $(msCountry).on('selectionchange', function(e,ms,records) {
            initialize(mapOptions);
            $.each(records, function( key, row ) {
                paintCountry(row.pais_iata, mapOptionsLoc, null, null);
            });
        });*/

        initialize(mapOptions);


        $('#searchAgreementForm').on('submit', function(event){
            
            var countries = msCountry.getValue();
            var products  = msProduct.getValue();

            if ( countries.length > 0 || products.length > 0) {

                var form = $(this);
                var btn = $('#searchAgreementSubmit');
                btn.button('loading');

                $.ajax({
                    type:"POST"
                    ,url:'acuerdo/publicSearch'
                    ,data:{
                        products: products,
                        countries: countries
                    }
                    ,dataType:"json"
                    ,success:function(data){
                        if(data.success){
                            var records = data.data;
                            $.each(records, function( key, row ) {

                                var countriesIata = row.paises_iata.split(',');

                                $.each(countriesIata, function( i, iataCode ) {
                                    console.log(iataCode);
                                    paintCountry(iataCode, mapOptionsLoc, null, null);
                                });

                            });
                            
                        } else {
                            $("#modal-error-msg").html(data.error);
                            $('#errorModal').modal('show');
                        }
                    }
                }).always(function(){
                    btn.button('reset');
                });
            }
        
            event.preventDefault();
        });

	}

});


function initialize(mapOptions) {
    
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


    

    /*paintCountry('CO', mapOptionsLoc, '/img/flag_colombia.png', new google.maps.LatLng(0,-85));*/
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
