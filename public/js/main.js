var mapStyles = {
	1: {
		lineColor: "#FF0000",
		lineWidth: 1,
		lineOpacity: 1,
		backgroundColor: "#FF0000",
		backgroundOpacity: 0.5
	},
	2: {
		lineColor: "#996633",
		lineWidth: 1,
		lineOpacity: 1,
		backgroundColor: "#FFCC99",
		backgroundOpacity: 0.5
	},
	3: {
		lineColor: "#0000FF",
		lineWidth: 1,
		lineOpacity: 1,
		backgroundColor: "#0000FF",
		backgroundOpacity: 0.5
	},
	4: {
		lineColor: "#00FFFF",
		lineWidth: 1,
		lineOpacity: 1,
		backgroundColor: "#00FFFF",
		backgroundOpacity: 0.5
	},
	5: {
		lineColor: "#996600",
		lineWidth: 1,
		lineOpacity: 1,
		backgroundColor: "#996600",
		backgroundOpacity: 0.5
	},
	6: {
		lineColor: "#660000",
		lineWidth: 1,
		lineOpacity: 1,
		backgroundColor: "#660000",
		backgroundOpacity: 0.5
	}
};

var mapOptions = {
	zoom: 2,
	center: new google.maps.LatLng(35, 0)
};

jQuery(function($) {

	$(document).ajaxStart(function(){
		$.blockUI({ message: '<h4 class="margin-top10 margin-bottom10"><i class="fa fa-spinner fa-spin"></i> Espere por favor</h4 class= "nomargin">' });
	}).ajaxComplete(function() {
		$.unblockUI();
	});

	var is_msie = (navigator.appVersion.indexOf("MSIE")!=-1) ? true : false;
	var map;

	if ( $('#full-slider').length > 0 ) {
		$('footer').hide();
		$( '.navbar' ).addClass( 'bottom' )
		
		$('#full-slider').maximage({
			cycleOptions: {
				fx: 		'fade',
				speed: 		1000,
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
			allowFreeEntries: false
			,data: 'posicion/listInAgreement'
			,displayField: 'posicion'
			,highlight:true
			,maxSelection: 1
			,minChars:2
			,placeholder: 'Select...'
			,mode: 'remote'
			,resultsField: 'data'
			,selectionPosition: 'bottom'
			,selectionStacked: true
			,typeDelay: 600
			,useZebraStyle: true
			,valueField: 'id_posicion'
			,selectionRenderer: function(data){
				return data.posicion + ' (<b>' + data.id_posicion + '</b>)';
			}
			,renderer: function(data){
				return '<div style="padding: 5px; overflow:hidden;">' +
					'<div style="float: left; margin-left: 5px">' +
						'<div style="font-weight: bold; color: #333; font-size: 11px; line-height: 11px">' + data.id_posicion + '</div>' +
						'<div style="color: #999; font-size: 10px">' + data.posicion + '</div>' +
					'</div>' +
				'</div><div style="clear:both;"></div>'; // make sure we have closed our dom stuff
			}
		});
		
		$(msProduct).on('beforeload', function(c){
			var trade = $("#searchAgreementForm input[name=agreementTrade]:checked").val();
			this.setDataUrlParams({trade: trade});
		});
		
		var msCountry = $('#ms-filter-country').magicSuggest({
			allowFreeEntries: false
			,data: 'pais/listInAgreement'
			,displayField: 'pais'
			,highlight:true
			,maxSelection: 5
			,placeholder: 'Select...'
			,mode: 'remote'
			,resultsField: 'data'
			,selectionPosition: 'bottom'
			,selectionStacked: true
			,typeDelay: 600
			,useZebraStyle: true
			,valueField: 'id_pais'
			,selectionRenderer: function(data){
				return data.pais + ' (<b>' + data.pais_iata + '</b>)';
			}
		});

		$(msCountry).on('beforeload', function(c){
			var trade = $("#searchAgreementForm input[name=agreementTrade]:checked").val();
			this.setDataUrlParams({trade: trade});
		});

		initialize(mapOptions);

		$('#searchAgreementForm').on('submit', function(event){
			
			var countries = msCountry.getValue();
			var products  = msProduct.getValue();
			var trade     = $("#searchAgreementForm input[name=agreementTrade]:checked").val();
			
			if ( countries.length > 0 || products.length > 0) {
				
				initialize(mapOptions);

				var form = $(this);
				var btn = $('#searchAgreementSubmit');
				btn.button('loading');

				$.ajax({
					type:"POST"
					,url:'acuerdo/publicSearch'
					,data:{
						products: products,
						countries: countries,
						trade: trade
					}
					,dataType:"json"
					,success:function(data){
						if(data.success){
							var records = data.data;
							var index   = 0;
							var keys    = Object.keys(mapStyles);
							$.each(records, function( key, row ) {

								index = ( (index + 1) > keys.length ) ? (index - keys.length) : (index + 1);

								var agreement = row.acuerdo_id;
								var mapStyle  = mapStyles[index];

								if (row.paises_iata) {
									var countriesIata = row.paises_iata.split(',');
									
									$.each(countriesIata, function( i, iataCode ) {
										paintCountry(iataCode, mapStyle, null, null, agreement);
									});
								} else {
									paintCountry(row.pais_iata, mapStyle, null, null, agreement);
								}
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
	}// End of if ( $('#map-canvas').length > 0 )

	if ( $('#grid-quota').length > 0 ) {

		var msCountry = $('#ms-filter-country').magicSuggest({
			allowFreeEntries: false
			,data: 'pais/listInAgreement'
			,displayField: 'pais'
			,highlight:true
			,maxSelection: 1
			,noSuggestionText: 'Cero resultados para mostrar'
			,placeholder: 'Seleccione un país'
			,mode: 'remote'
			,required: true
			,resultsField: 'data'
			,selectionPosition: 'bottom'
			,selectionStacked: true
			,typeDelay: 600
			,useZebraStyle: true
			,valueField: 'id_pais'
			,selectionRenderer: function(data){
				return data.pais + ' (<b>' + data.pais_iata + '</b>)';
			}
		});

		$(msCountry).on('beforeload', function(c){
			var trade = $("#searchQuotaForm input[name=agreementTrade]:checked").val();
			this.setDataUrlParams({trade: trade});
		});

		var msProduct = $('#ms-filter-product').magicSuggest({
			allowFreeEntries: false
			,data: 'posicion/listInAgreement'
			,disabled: true
			,displayField: 'posicion'
			,highlight:true
			,maxSelection: 1
			//,minChars:2
			,noSuggestionText: 'Cero resultados para mostrar'
			,placeholder: 'Seleccione un producto'
			,mode: 'remote'
			,required: true
			,resultsField: 'data'
			,selectionPosition: 'bottom'
			,selectionStacked: true
			,typeDelay: 600
			,useZebraStyle: true
			,valueField: 'id_posicion'
			,selectionRenderer: function(data){
				return data.posicion + ' (<b>' + data.id_posicion + '</b>)';
			}
			,renderer: function(data){
				return '<div style="padding: 5px; overflow:hidden;">' +
					'<div style="float: left; margin-left: 5px">' +
						'<div style="font-weight: bold; color: #333; font-size: 11px; line-height: 11px">' + data.id_posicion + '</div>' +
						'<div style="color: #999; font-size: 10px">' + data.posicion + '</div>' +
					'</div>' +
				'</div><div style="clear:both;"></div>'; // make sure we have closed our dom stuff
			}
		});

		$(msCountry).on('selectionchange', function(e,m,a,b){
			var countries = msCountry.getValue();
			var trade     = $("#searchAgreementForm input[name=agreementTrade]:checked").val();
			if ( countries.length > 0 ) {
				msProduct.setDataUrlParams({
					trade: trade,
					countries: countries
				});
				msProduct.enable();
			} else {
				msProduct.disable();
			}
		});

		$('#searchQuotaForm').on('submit', function(event){
			
			var countries = msCountry.getValue();
			var products  = msProduct.getValue();
			var trade     = $("#searchQuotaForm input[name=agreementTrade]:checked").val();
			
			if ( countries.length > 0 && products.length > 0) {
				
				var form = $(this);
				var btn = $('#searchQuotaSubmit');
				btn.button('loading');

				$.ajax({
					type:"POST"
					,url:'acuerdo_det/publicSearch'
					,data:{
						products: products,
						countries: countries,
						trade: trade
					}
					,dataType:"json"
					,success:function(data){
						if(data.success){
							var records = data.data;
							var index   = 0;
							$("#grid-quota").html(data.html);
							$('#pagination').twbsPagination({
						        totalPages: data.total,
						        visiblePages: 5,
						        first: '&laquo;',
						        prev: '&lsaquo;',
						        next: '&rsaquo;',
						        last: '&raquo;',
						        onPageClick: function (event, page) {
						        	console.log(page);
						        	//$('#agreementDetTabs #agreementDet_' + page).tab('show');
						        	$('#agreementDetTabs li:eq(' + page + ') a').tab('show');
						        }
						    });
						    Holder.run();

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

	}// End of if ( $('#grid-quota').length > 0 )

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

}

function paintCountry(countryCode, countryOptions, countryIcon, countryPosition, agreement) {
	
	var countryObj = country[countryCode];

	if (countryObj) {

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
			$.ajax({
				type:"POST"
				,url:'acuerdo/listIdPublic'
				,data:{
					acuerdo_id: agreement
				}
				,dataType:"json"
				,success:function(data){
					if(data.success){
						$("#agreementModal .modal-content").html(data.html);
						$('#agreementModal').modal('show');
						Holder.run();
					} else {
						$("#modal-error-msg").html(data.error);
						$('#errorModal').modal('show');
					}
				}
			});
		});
	}
}
