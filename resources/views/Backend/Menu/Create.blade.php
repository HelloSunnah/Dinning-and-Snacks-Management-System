@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1 class="mb-4">Create New Menu</h1>

        <form action="{{ route('menu.store') }}" method="POST">
            @csrf

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display Success Message -->
            @if (session('success'))
                <div class="alert alert-success mb-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="form-group">
                <label for="type" class="font-weight-bold">Menu Type:</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="" disabled selected>Select Menu Type</option>
                    <option value="snack">Snack</option>
                    <option value="lunch">Lunch</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name" class="font-weight-bold">Menu Name:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter menu name" required>
            </div>

            <div class="form-group">
                <label for="unit" class="font-weight-bold">Unit:</label>
                <select id="unit" name="unit" class="form-control" required>
                    <option value="" disabled selected>Select Unit</option>
                    <option value="kg">Kg</option>
                    <option value="pcs">Pcs</option>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity_per_person" class="font-weight-bold">Quantity per Person  <span style="color: red;">(in grams if unit is Kg):</span></label>
                <input type="number" id="quantity_per_person" name="quantity_per_person" class="form-control" placeholder="Enter quantity" required>
            </div>

            <div class="form-group d-flex justify-content-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-4 py-2">Create Menu</button>
            </div>
        </form>
    </div>
@endsection
