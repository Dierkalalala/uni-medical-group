<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Hostel's project</title>

    <style>
        #map {
            width: 500px;
            height: 500px;
        }

        #marker {
            background-image: url('icon.png');
            width: 33px;
            height: 36px;
            position: absolute;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .header {
            padding: 5px;
        }

        body {
            background-color: #F1F3F4;
        }

        ul {
            padding-left: 0;
        }
    </style>

</head>
<body>

@yield('content')
<div class="container">
    <div class="row align-items-center justify-content-center text-center">
        <div class="col-lg-6">
            <p class="header">Place the mark to the place where your meeting is going
                to be conducted and we will find the closest hostel for you</p>

            <p class="header">Approximate distance to closest hotel is <span class="distance"></span> km</p>
            <a href="/" class="btn btn-success mb-3">Refresh page</a>
            <div id="map" style="margin: 0 auto;"></div>
            <div id="marker"></div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js"
        integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=f6d19d9a-bfbb-488c-b7ef-7f434633c6a7"
        type="text/javascript"></script>
<script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>

<script>
    jQuery(function () {
        ymaps.ready(init);
    });

    let index = 0;

    const messages = [
        'hey, you better stop playing with me. Drag the map point to the map',
        'do I have to warn you once again?',
        'This is the last time when you are able to do it.',
        'Once again and I will punish you'
    ]

    function init() {
        var map = new ymaps.Map('map', {
                center: [41.310755, 69.284189],
                zoom: 13
            }, {
                searchControlProvider: 'yandex#search'
            }),
            markerElement = jQuery('#marker'),
            dragger = new ymaps.util.Dragger({
                // Dragger will automatically run when the user clicks on the 'marker' element.
                autoStartElement: markerElement[0]
            }),
            // The offset of the marker relative to the cursor.
            markerOffset,
            markerPosition;

        dragger.events
            .add('start', onDraggerStart)
            .add('move', onDraggerMove)
            .add('stop', onDraggerEnd);

        function onDraggerStart(event) {
            var offset = markerElement.offset(),
                position = event.get('position');
            // Saving the offset of the marker relative to the drag starting point.
            markerOffset = [
                position[0] - offset.left,
                position[1] - offset.top
            ];
            markerPosition = [
                position[0] - markerOffset[0],
                position[1] - markerOffset[1]
            ];

            applyMarkerPosition();
        }

        function onDraggerMove(event) {
            applyDelta(event);
        }

        function onDraggerEnd(event) {
            applyDelta(event);
            markerPosition[0] += markerOffset[0];
            markerPosition[1] += markerOffset[1];
            // Converting page coordinates to global pixel coordinates.
            var markerGlobalPosition = map.converter.pageToGlobal(markerPosition),
                // Getting the center of the map in global pixel coordinates.
                mapGlobalPixelCenter = map.getGlobalPixelCenter(),
                // Getting the size of the map container on the page.
                mapContainerSize = map.container.getSize(),
                mapContainerHalfSize = [mapContainerSize[0] / 2, mapContainerSize[1] / 2],
                // Calculating the map boundaries in global pixel coordinates.
                mapGlobalPixelBounds = [
                    [mapGlobalPixelCenter[0] - mapContainerHalfSize[0], mapGlobalPixelCenter[1] - mapContainerHalfSize[1]],
                    [mapGlobalPixelCenter[0] + mapContainerHalfSize[0], mapGlobalPixelCenter[1] + mapContainerHalfSize[1]]
                ];
            // Checking that the dragger finished working in a visible area of the map.
            if (containsPoint(mapGlobalPixelBounds, markerGlobalPosition)) {
                // Now we'll convert the global pixel coordinates to geocoordinates with the current zoom level of the map.
                var geoPosition = map.options.get('projection').fromGlobalPixels(markerGlobalPosition, map.getZoom());


                axios
                    .post('/clinics', {
                        lat: geoPosition[0],
                        lng: geoPosition[1]
                    })
                    .then(res => {
                        let arrayOfClinics = Object.keys(res.data).map((objectKey) => res.data[objectKey])
                        res.data = arrayOfClinics.sort((a, b) => {
                            return a.closestIndex - b.closestIndex
                        })
                        var multiRoute = new ymaps.multiRouter.MultiRoute({
                            // The description of the reference points on the multi-stop route.
                            referencePoints: [
                                [geoPosition[0], geoPosition[1]],
                                [arrayOfClinics[0].lat, arrayOfClinics[0].lng],
                            ],
                            // Routing options.
                            params: {
                                // Limit on the maximum number of routes returned by the router.
                                results: 1
                            }
                        }, {
                            // Automatically set the map boundaries so the entire route is visible.
                            boundsAutoApply: true
                        });

                        map.geoObjects.add(multiRoute);

                        function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
                            var R = 6371; // Radius of the earth in km
                            var dLat = deg2rad(lat2-lat1);  // deg2rad below
                            var dLon = deg2rad(lon2-lon1);
                            var a =
                                Math.sin(dLat/2) * Math.sin(dLat/2) +
                                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                                Math.sin(dLon/2) * Math.sin(dLon/2)
                            ;
                            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                            var d = R * c; // Distance in km
                            return d;
                        }

                        function deg2rad(deg) {
                            return deg * (Math.PI/180)
                        }

                        const distanceMOWBKK = getDistanceFromLatLonInKm(
                            geoPosition[0], geoPosition[1], arrayOfClinics[0].lat, arrayOfClinics[0].lng
                        )

                        document.querySelector('.distance').innerHTML = (distanceMOWBKK * 1.5).toFixed(0)

                    })

                marker.classList.add('d-none')

                index = 0;


            } else {
                alert(messages[index])
                index++
            }
        }

        function applyDelta(event) {
            // The 'delta' field contains the difference between the positions of the current and previous dragger events.
            var delta = event.get('delta');
            markerPosition[0] += delta[0];
            markerPosition[1] += delta[1];
            applyMarkerPosition();
        }

        function applyMarkerPosition() {
            markerElement.css({
                left: markerPosition[0],
                top: markerPosition[1]
            });
        }

        function containsPoint(bounds, point) {
            return point[0] >= bounds[0][0] && point[0] <= bounds[1][0] &&
                point[1] >= bounds[0][1] && point[1] <= bounds[1][1];
        }


        const coordinateInput = document.querySelector('.map-coordinates')
        const coordinateString = coordinateInput.getAttribute('data-coordinates').trim();
        const coordinates = coordinateString.split('| ');
        const blueCollection = new ymaps.GeoObjectCollection(null, {
            preset: 'islands#blueIcon',
            iconColor: '#735184'
        })
        Array.from(coordinates).forEach((coordinate, index) => {
            let coordinateArray = coordinate.trim().split(',');
            const myPlaceMark = new ymaps.Placemark(coordinateArray)
            blueCollection.add(myPlaceMark);
        })
        map.geoObjects.add(blueCollection);

    }

</script>

</body>
</html>
