@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Edit Manpower</h1>

        <form action="{{ route('manpower.update', $manpower->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">

                <div class="row">
                    <div class="col-md-6"> 
                        <label for="group_name">Shift</label>
                        <input type="text" readonly id="shift" name="group_name" class="form-control" value="{{ old('shift', $manpower->shift) }}" required>
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
