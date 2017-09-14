var map;
var lat = -17.786939402187738;
var lon = -63.179969787597656;
var mk = false;
var lupa = 12;

function AbrirMapa(x, y) {
    var modal = $('.modal.auxiliar');
    var cord = $('#inputMapaDomicilio').val();
    if(cord!=""){
	    var data = cord.split(",");
	    x=data[0];
	    y=data[1];
    }
    modal.css({display: 'block'});
    var titulo = $('#titulo_aux_modal');
    titulo.html("Haga click en la ubicación de su empresa");
    $("#modal_aux").css({height: '480px'}, {position: 'relative'}, {overflow: 'hidden'});
    $("#modal_aux").css('background-color', 'rgb(229, 227, 223)');
    $("#modal_aux").css('-webkit-transform', 'translateZ(0px)');
    if (x != null) {
        lat = x;
        lon = y;
        lupa = 15;
        mk = true;

    }

    iniciarmapa();

}
function CerrarModal() {
    var modal = $('.modal');
    modal.css({display: 'none'});
}
function iniciarmapa(lat, lon) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyDY0kkJiTPVd2U7aTOAwhc9ySH6oHxOIYM&sensor=false&callback=initialize";
    document.body.appendChild(script);
}

function initialize()
{

    var mapProp = {
        center: new google.maps.LatLng(lat, lon),
        zoom: lupa,
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("modal_aux"), mapProp);
    if (mk) {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lon),
        });

        marker.setMap(map);
    }
    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);
    });
}

function placeMarker(location) {
    var marker = new google.maps.Marker({
        position: location,
        map: map,
    });
    var infowindow = new google.maps.InfoWindow({
        content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
    });
    var cord = location.lat() + "," + location.lng();

    $('#inputMapaDomicilio').val(cord);
    //infowindow.open(map,marker);
    CerrarModal();

}
