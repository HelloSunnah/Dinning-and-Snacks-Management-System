@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Edit Menu</h1>

        <form action="{{ route('menu.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-row">
                <!-- Menu Type -->
                <div class="form-group col-md-6">
                    <label for="type" class="font-weight-bold">Menu Type:</label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="snack" {{ $menu->type == 'snack' ? 'selected' : '' }}>Snack</option>
                        <option value="lunch" {{ $menu->type == 'lunch' ? 'selected' : '' }}>Lunch</option>
                    </select>
                </div>

                <!-- Unit -->
                <div class="form-group col-md-6">
                    <label for="unit" class="font-weight-bold">Unit:</label>
                    <select id="unit" name="unit" class="form-control" required>
                        <option value="kg" {{ $menu->unit == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="pcs" {{ $menu->unit == 'pcs' ? 'selected' : '' }}>pcs</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <!-- Menu Name -->
                <div class="form-group col-md-6">
                    <label for="name" class="font-weight-bold">Menu Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required value="{{ $menu->name }}">
                </div>

                <!-- Quantity Per Person -->
                <div class="form-group col-md-6">
                    <label for="quantity_per_person" class="font-weight-bold">Quantity Per Person (Pcs or Gram):</label>
                    <input type="number" name="quantity_per_person" id="quantity_per_person" class="form-control" required value="{{ $menu->quantity_per_person }}">
                </div>
            </div>

            <!-- Update Button -->
            <div class="form-group d-flex justify-content-center" style="padding: 10px;">
                <button type="submit" class="btn btn-primary btn-lg" >Update Menu</button>
            </div>
        </form>
    </div>
@endsection
