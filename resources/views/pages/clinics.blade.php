@extends('master.master')

@section('content')
    <main>
        <div class="container pt-4">
            <ul style="padding-left: 0;" class="list-group list-group-horizontal">
                <li style="width: 30%" class="list-group-item">Name of the patient:</li>
                <li style="width: 100%" class="list-group-item">{{$patientName}}</li>
            </ul>
            <ul style="padding-left: 0;" class="list-group list-group-horizontal-sm">
                <li style="width: 30%" class="list-group-item">Address of the patient:</li>
                <li style="width: 100%" class="list-group-item">{{$patientDistrict}} - {{$patientAddress}}</li>
            </ul>
            <h3>
                Closest clinics in descending order
            </h3>
            <ul class="list-group">
                @foreach($clinics as $clinic)
                    <li class="list-group-item">{{$clinic->name}} - <a href="tel:{{$clinic->phone_number}}">{{$clinic->phone_number}}</a></li>
                @endforeach
            </ul>

            <div class="mt-2">
                <div class="map-coordinates"
                     data-my-coordinates="{{$myCoordinates}}"
                     data-names="@foreach($clinics as $clinic){{$clinic->name}},@endforeach"
                     data-coordinates="@foreach($clinics as $clinic){{$clinic->coordinates}}@if(!$loop->last)|@endif @endforeach"></div>
                <div style="height: 400px;" id="map"></div>
            </div>

            <a class="btn btn-primary mt-2 mb-4" href="{{route('main')}}">Get back to the main page</a>
        </div>
    </main>
@endsection
