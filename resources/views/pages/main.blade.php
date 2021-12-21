@extends('master.master')

@section('content')
    <main>
        <div class="container">
            <form method="POST" action="{{route('getClinics')}}" class="pt-4 pb-4 needs-validation">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input name="name" required type="text" class="form-control" id="name">
                    <div id="emailHelp" class="form-text">Enter the name</div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input name="address" required type="text" class="form-control" id="address">
                    <div id="emailHelp" class="form-text">Enter the address</div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Index</label>
                    <select name="index" required class="form-select" aria-label="Default select example">
                        <option value="" disabled selected>Indexes</option>
                        @foreach($boxes as $box)
                            <option value="{{$box->id}}">{{$box->name}}</option>
                        @endforeach
                    </select>
                    <div id="emailHelp" class="form-text">Chose the closest index</div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Clinic type</label>
                    <select name="clinic" required class="form-select" aria-label="Default select example">
                        <option value="" disabled selected>Clinic types</option>
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                    <div id="emailHelp" class="form-text">Chose the clinic types</div>
                </div>

                <button type="submit" class="btn btn-primary">Look for closest clinic</button>
            </form>
        </div>
    </main>
@endsection
