@extends('master.master')

@section('content')
    <main>
        <div class="container pt-4">

            <h3>
                Name of the patient: {{$patientName}}
            </h3>

            <h3>
                Address of the patient: {{$patientAddress}}
            </h3>

            <h3>
                Closest clinics in descending order
            </h3>
            <ul class="list-group">
                @foreach($clinics as $clinic)
                    <li class="list-group-item">{{$clinic->name}}</li>
                @endforeach
            </ul>

            <a class="btn btn-primary mt-2 mb-4" href="{{route('main')}}">Get back to the main page</a>
        </div>
    </main>
@endsection
