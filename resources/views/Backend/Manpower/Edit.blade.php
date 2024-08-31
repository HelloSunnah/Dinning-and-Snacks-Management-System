@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Edit Manpower</h1>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('manpower.update', $manpower->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">

                <div class="row">
                    <div class="col-md-6">
                        <label for="shift">Shift</label>
                        <input type="text" id="shift" name="shift" class="form-control" value="{{ old('shift', $manpower->shift) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="member">Member</label>
                        <input type="number" id="member" name="member" class="form-control" value="{{ old('member', $manpower->member) }}" required>
                    </div>
                </div>

                <!-- Center the button -->
                <div class="d-flex justify-content-center mt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

            </div>
        </form>
    </div>
@endsection
