@extends('master.master')

@section('content')
    <main>
        <div class="map-coordinates"
             data-coordinates="@foreach($clinics as $clinic){{$clinic->getCoordinates()[0]['lat']}},{{$clinic->getCoordinates()[0]['lng']}}@if(!$loop->last)|@endif @endforeach">
        </div>
    </main>
@endsection
