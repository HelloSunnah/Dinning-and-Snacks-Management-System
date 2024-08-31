@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Create New Menu</h1>

        <form action="{{ route('menu.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="type">Menu Type:</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="snack">Snack</option>
                    <option value="lunch">Lunch</option>
                </select>
            </div>
               <div class="form-group">
                <label for="type">Menu</label>
                <input type="text" name="name"  class="form-control" required>
            </div>

            <div class="form-group">
                <label for="unit">Unit:</label>
                <select id="unit" name="unit" class="form-control" required>
                    <option value="kg">Kg</option>
                    <option value="pcs">Pcs</option>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity_per_person">Quantity:Per Persion</label>
                <input type="number" id="quantity_per_person" name="quantity_per_person" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Menu</button>
        </form>
    </div>
@endsection
