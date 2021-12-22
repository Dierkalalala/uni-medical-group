<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Medical Project</title>

    <style>
        body {
            background-color: #F1F3F4;
        }
        ul{
            padding-left: 0;
        }
    </style>

</head>
<body>

@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=f6d19d9a-bfbb-488c-b7ef-7f434633c6a7" type="text/javascript"></script>
<script>
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

    try {
        if (document.querySelector('#map')) {
            ymaps.ready(init);
            function init() {
                var myMap = new ymaps.Map("map", {
                    center: [41.3775, 64.5853],
                    zoom: 5
                }, {
                    searchControlProvider: 'yandex#search'
                })
                const coordinateInput = document.querySelector('.map-coordinates')
                const coordinateString = coordinateInput.getAttribute('data-coordinates').trim();
                const namesArray = coordinateInput.getAttribute('data-names').split(',');
                const myCoordinates = coordinateInput.getAttribute('data-my-coordinates').split(',');
                const coordinates = coordinateString.split('| ');
                const blueCollection = new ymaps.GeoObjectCollection(null, {
                    preset: 'islands#blueIcon',
                    iconColor: '#735184'
                })
                Array.from(coordinates).forEach((coordinate, index) => {
                    let coordinateArray = coordinate.trim().split(',');
                    const myPlaceMark = new ymaps.Placemark(coordinateArray)
                    myPlaceMark.properties.set({
                        balloonContent: `<span>${namesArray[index]}</span>`
                    })
                    blueCollection.add(myPlaceMark);
                })
                const grayCollection = new ymaps.GeoObjectCollection(null, {
                    preset: 'islands#greenDotIconWithCaption',
                })
                const myPlaceMark = new ymaps.Placemark(myCoordinates)
                myPlaceMark.properties.set({
                    balloonContent: `<span>My index</span>`
                })
                grayCollection.add(myPlaceMark);
                myMap.geoObjects.add(blueCollection);
                myMap.geoObjects.add(grayCollection);


            }
        } else {
            console.log('not inited');
        }
    } catch (e) {
        console.log(e)
    }
</script>
</body>
</html>
