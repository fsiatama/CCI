<script src="https://maps.googleapis.com/maps/api/js?v=3" type="text/javascript"></script>
<script type="text/javascript">
    (function () {
        if (typeof (GLatLng) === "undefined") {
            GLatLng = google.maps.LatLng;
        }
    })();
</script>
<script src="http://countrypoints.googlecode.com/files/countrypoints.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-12">
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
                center: new google.maps.LatLng(30, 0)
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

            paintCountry('CO', mapOptionsLoc, map);
            paintCountry('AR', mapOptionsPen, map);
            paintCountry('VE', mapOptionsAso, map);
            paintCountry('MX', mapOptionsAso, map);
            paintCountry('US', mapOptionsAso, map);
        }

        function paintCountry(code, param, mapa) {
            var gMap = mapa || map;
            var pais = country[code];
            
            var imag = new google.maps.Polygon({
                paths: pais.coord,
                strokeColor: param.lineColor,
                strokeOpacity: param.lineOpacity,
                strokeWeight: param.lineWidth,
                fillColor: param.backgroundColor,
                fillOpacity: param.backgroundOpacity
            });
            imag.setMap(gMap);

            google.maps.event.addListener(imag, 'click', function (event) {
                //alert the index of the polygon
                alert("PAIS: " + pais.desc);
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);

    })();
</script>