<script src="https://maps.googleapis.com/maps/api/js?v=3" type="text/javascript"></script>
<script src="/js/google.maps.countrypoints.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong class="">Información general de los Tratados de Libre Comercio (TLC)</strong>
            </div>
            <div class="panel-body">
                <div id="map-canvas" style="height: 600px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function () {
        var map;

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
                lineColor: "#FF0000", // Contorno del pa챍
                lineWidth: 1, // Tamaño la l쭥a de contorno
                lineOpacity: 1, // Opacidad de la l쭥a de contorno
                backgroundColor: "#FF0000", // Color del fondo
                backgroundOpacity: 0.2 // Opacidad del fondo.
            };
            var mapOptionsAso = {
                lineColor: "#0000FF", // Contorno del pa챍
                lineWidth: 1, // Tamaño la l쭥a de contorno
                lineOpacity: 1, // Opacidad de la l쭥a de contorno
                backgroundColor: "#0000FF", // Color del fondo
                backgroundOpacity: 0.2 // Opacidad del fondo.
            };
            var mapOptionsPen = {
                lineColor: "#FFA500", // Contorno del pa챍
                lineWidth: 1, // Tamaño la l쭥a de contorno
                lineOpacity: 1, // Opacidad de la l쭥a de contorno
                backgroundColor: "#FFA500", // Color del fondo
                backgroundOpacity: 0.2 // Opacidad del fondo.
            };

            paintCountry('CO', mapOptionsLoc, '<?= URL_RAIZ ?>img/flag_colombia.png', new google.maps.LatLng(0,-85));
            paintCountry('VE', mapOptionsAso, null, null);
            paintCountry('CL', mapOptionsAso, null, null);
            paintCountry('US', mapOptionsAso, null, null);
            paintCountry('MX', mapOptionsPen, '<?= URL_RAIZ ?>img/flag_mexico.png', new google.maps.LatLng(19.2600029,-116.5110027,7));
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

        google.maps.event.addDomListener(window, 'load', initialize);

    })();
</script>