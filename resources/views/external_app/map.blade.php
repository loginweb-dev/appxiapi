<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Appxi</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" type="image/png" href="https://mystorage.loginweb.dev/storage/Projects/appxi/icon-512x512.png">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        
        <!-- Google maps -->
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfhTHyaCn2bXEKvT13E0YEutlQY1bmfoM&callback=initMap&libraries=&v=weekly" defer></script>
        <style type="text/css">
            /* Always set the map height explicitly to define the size of the div
            * element that contains the map. */
            #map {
                height: 100%;
            }
            /* Optional: Makes the sample page fill the window. */
            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
        </style>
        <script>
            // Note: This example requires that you consent to location sharing when
            // prompted by your browser. If you see the error "The Geolocation service
            // failed.", it means you probably did not give permission for the browser to
            // locate you.
            var map, infoWindow, origin, destiny;

            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: -14.835473, lng: -64.904180 },
                    zoom: 15,
                });

                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            origin = new google.maps.Marker({
                                position: pos,
                                map: map,
                            });
                            map.setCenter(pos);

                            $('#form-service input[name="origin_latitude"]').val(pos.lat);
                            $('#form-service input[name="origin_longitude"]').val(pos.lng);
                        },
                        () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );

                    map.addListener("click", (e) => {
                        placeMarkerAndPanTo(e.latLng, map);
                    });
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            }

            function placeMarkerAndPanTo(latLng, map) {
                if (destiny && destiny.setMap) {
                    destiny.setMap(null);
                }
                destiny = new google.maps.Marker({
                    position: latLng,
                    // icon: 'https://maps.google.com/mapfiles/kml/shapes/parking_lot_maps.png',
                    map: map,
                });
                map.panTo(latLng);
                $('.section-alert').fadeOut('slow', function(){
                    $('.section-vehicle-types').fadeIn();
                });
                
                $('#form-service input[name="destiny_latitude"]').val(latLng.toJSON().lat);
                $('#form-service input[name="destiny_longitude"]').val(latLng.toJSON().lng);
            }

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.setContent(
                    browserHasGeolocation
                    ? "Error: The Geolocation service failed."
                    : "Error: Your browser doesn't support geolocation."
                );
                infoWindow.open(map);
            }
        </script>
    </head>
    <body>
        <div id="map"></div>
        <form id="form-service" action="{{ route('api.external.service.store') }}" method="post">
            <input type="hidden" name="id" value="{{ $id }}" />
            <input type="hidden" name="origin_latitude" />
            <input type="hidden" name="origin_longitude" />
            <input type="hidden" name="destiny_latitude" />
            <input type="hidden" name="destiny_longitude" />
            <div class="section-confirm text-center">
                <div class="section-alert">
                    <p>Presiona sobre la ubicación a la que quieras trasladarte.</p>
                </div>
                <div class="section-vehicle-types">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        @foreach($vehicle_types as $vehicle)
                            <label class="btn btn-outline-primary btn-vehicle-type">
                                <input type="radio" name="vehicle_type_id" id="vehicle-{{ $vehicle->id }}" value="{{ $vehicle->id }}" autocomplete="off"> {{ $vehicle->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="section-payment-types">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        @foreach($payment_types as $payment)
                            <label class="btn btn-outline-primary btn-payment-type">
                                <input type="radio" name="payment_type_id" id="payment-{{ $payment->id }}" value="{{ $payment->id }}" autocomplete="off"> {{ $payment->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="section-btn">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Pedir taxi <i class="fas fa-map-marker-alt"></i></button>
                </div>
            </div>
        </form>
    </body>

    <style>
        .section-confirm{
            position: fixed;
            bottom: 30px;
            right: 0px;
            left: 0px;
            z-index: 100;
            padding-left: 20px;
            padding-right: 20px
        }
        .section-alert{
            padding-top: 10px;
            padding-bottom: 10px;
            background: linear-gradient(to bottom right, #335372, #5D92C6);
            color: white
        }
        .section-vehicle-types, .section-payment-types{
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px
        }
        .section-btn, .section-vehicle-types, .section-payment-types{
            display: none
        }
    </style>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- SweetAler2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function(){
            $('.btn-vehicle-type').click(function(){
                $('.section-vehicle-types').fadeOut('slow', function(){
                    $('.section-payment-types').fadeIn();
                });
            });

            $('.btn-payment-type').click(function(){
                $('.section-payment-types').fadeOut('slow', function(){
                    $('.section-btn').fadeIn();
                });
            });

            $('#form-service').submit(function(e){
                e.preventDefault();
                let url = $(this).attr('action');
                let data = $(this).serialize();
                $.post(url, data, function(res){
                    if(res.data){
                        Swal.fire({
                            icon: 'success',
                            title: 'Bien hecho!',
                            text: 'Tu solicitud de taxi ha sido envida',
                            confirmButtonText: '<i class="fa fa-thumbs-up"></i> Listo!',
                            // footer: '<a href>Why do I have this issue?</a>'
                        });
                        setTimeout(() => {
                            window.close()
                        }, 3000);
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Ha ocurrido un error al solicitar su taxi',
                            confirmButtonText: '<i class="far fa-frown"></i> Entiendo!',
                            footer: '<a href="https://appxi.com.bo" target="_blank">Ir a soporte de atención al cliente</a>'
                        });
                    }
                });
            });
        });
    </script>
</html>