<?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 12:49 PM
 */
?>
@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Delivery Boy Location</h5>
                        <a href="{{URL::to('admin/delivery-boy/rider-location/'.$id)}}" class="btn btn-primary pull-right">Refresh</a>
                    <div class="ibox-content">
                        <div class="table-responsive">
                        <div id="map" style="width:100%;height:400px;">
                        </div>
                        <script>
                            function initMap() {
                                var riderLat =  '<?= $driverLat ?>';
                                var riderLong = '<?= $driverLong ?>';
                                var riderName = '<?= $rider->username ?>';
                                var myLatLng = {lat: parseFloat(riderLat), lng: parseFloat(riderLong)};
                                var map;
                                var bounds = new google.maps.LatLngBounds();
                                var mapOptions = {
                                    center: myLatLng,
                                    zoom: 16,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                map = new google.maps.Map(document.getElementById('map'), mapOptions);
                                map.setTilt(45);
                                var position = new google.maps.LatLng(riderLat, riderLong);// childData.lat, childData.lng  childData.l[0], childData.l[1]   24.916355, 79.581184
                                //console.log(position);
                                bounds.extend(position);
                                var image = {
                                    url: "<?=asset('/public/marker.png');?>",
                                    labelOrigin: new google.maps.Point(1,1),
                                    size: new google.maps.Size(35, 35),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(30, 40),
                                    scaledSize: new google.maps.Size(40, 40)
                                }
                                marker = new google.maps.Marker({
                                    position: position,
                                    map: map,
                                    title: 'Current location',

                                    label: {
                                        color: 'green', // <= HERE
                                        fontSize: '14px',
                                        fontWeight: '900',
                                        text:  riderName,
                                    },
                                    icon: image,
                                });
                                var contentString = '<div id="content">'+
                                                '<div id="siteNotice">'+
                                                '</div>'+
                                                '<h1 id="firstHeading" class="firstHeading">'+riderName+'</h1>'
                                        ;
                                var geocoder = new google.maps.Geocoder();
                                var infoWindow = new google.maps.InfoWindow();
                                google.maps.event.addListener(marker, 'click', function(evt) {
                                    var mark = this;
                                    geocoder.geocode({
                                        location: evt.myLatLng
                                    }, function(results, status) {
                                        if (status == "OK") {
                                            infoWindow.setContent(contentString+' '+results[0].formatted_address);
                                            infoWindow.open(map, mark);
                                        }
                                    });

                                    google.maps.event.addListenerOnce( marker, "visible_changed", function(marker, i) {
                                        infoWindow.close(marker);
                                    });
                                });
//                                var marker = new google.maps.Marker({
//                                    position: myLatLng,
//                                    map: map,
//                                    title: 'Current location'
//                                });
                                // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
                                var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                                    this.setZoom(16);
                                    google.maps.event.removeListener(boundsListener);
                                });
                            }
</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXeEpNyvOxirxB38hoys2_U7lTvQllS9g&callback=initMap"></script>
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->

    <script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
   
	
@stop
