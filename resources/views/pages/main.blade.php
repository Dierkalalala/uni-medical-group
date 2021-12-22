@extends('master.master')

@section('content')
    <main>
        <div class="container">
            <form method="POST" action="{{route('getClinics')}}" class="pt-4 pb-4 needs-validation">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name and Surname</label>
                    <input name="name" required type="text" class="form-control" id="name">
                    <div id="emailHelp" class="form-text">Enter the name and surname</div>
                </div>
                <div class="mb-3">
                    <label for="number" class="form-label">Phone number</label>
                    <input name="telephone" required type="tel" class="form-control" id="number">
                    <div id="emailHelp" class="form-text">Enter the phone number</div>
                </div>

                <div class="mb-3">
                    <label for="district" class="form-label">Chose the district</label>
                    <select name="district" id="district" required class="form-select" aria-label="Default select example">
                        <option value="" disabled selected>Districs</option>
                        <option value="Sergeli">Sergeli</option>
                        <option value="Mirzo-Ulugbek">Mirzo-Ulugbek</option>
                        <option value="Mirabad">Mirabad</option>
                        <option value="Bektemir">Bektemir</option>
                        <option value="Almazar">Almazar</option>
                        <option value="Yashnobod">Yashnobod</option>
                        <option value="Uchtepa">Uchtepa</option>
                        <option value="Shayhantahur">Shayhantahur</option>
                        <option value="Chilanzar">Chilanzar</option>
                        <option value="Yakkasaray">Yakkasaray</option>
                    </select>
                    <div id="emailHelp" class="form-text">Chose the district</div>
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
                    <select style="height: 240px" multiple name="type_id[]" required class="form-select" aria-label="Default select example">
                        <option value="" disabled selected>Clinic types</option>
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                    <div id="emailHelp" class="form-text">Search</div>
                </div>

                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </main>
@endsection
