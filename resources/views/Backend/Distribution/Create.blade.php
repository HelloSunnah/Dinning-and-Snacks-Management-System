@extends('Backend.master')

@section('content')
    <div class="container mt-5">
        <div class="card border-light shadow-sm">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Create Distribution Record</h1>

                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Display Success Message -->
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Display Error Message -->
                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('distribution.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="distribution_type" class="font-weight-bold">Distribution Type:</label>
                        <select id="distribution_type" name="distribution_type" class="form-control form-control-lg" required>
                            <option value="" disabled selected>Select Distribution Type</option>
                            <option value="lunch">Lunch</option>
                            <option value="snack">Snack</option>
                        </select>
                    </div>

                    <div class="form-group mb-4" id="time_of_day_container" style="display: none;">
                        <label for="time_of_day" class="font-weight-bold">Time of Day (for Snacks):</label>
                        <select id="time_of_day" name="time_of_day" class="form-control form-control-lg">
                            <option value="" disabled selected>Select Time of Day</option>
                            <option value="morning">Morning</option>
                            <option value="afternoon">Afternoon</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="shift" class="font-weight-bold">Shift(s):</label>
                        <select id="shift" name="shift[]" class="form-control form-control-lg" multiple required>
                            <!-- Options will be added dynamically -->
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="menu" class="font-weight-bold">Menu(s):</label>
                        <select id="menu" name="menu_id[]" class="form-control form-control-lg" multiple required>
                            <option value="" disabled>Select Menu(s)</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="day" class="font-weight-bold">Day(s):</label>
                        <select id="day" name="day[]" class="form-control form-control-lg" multiple required>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-4 py-2">Create Distribution Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const shiftElement = $('#shift');
            const timeOfDayContainer = $('#time_of_day_container');
            const timeOfDayElement = $('#time_of_day');
            const menuElement = $('#menu');
            const distributionTypeElement = $('#distribution_type');

            function updateShiftsAndMenu() {
                const type = distributionTypeElement.val();
                const time = timeOfDayElement.val();

                let shifts = [];

                if (type === 'lunch') {
                    shifts = ['General', 'A', 'B'];
                    timeOfDayContainer.hide();
                } else if (type === 'snack') {
                    timeOfDayContainer.show();
                    if (time === 'morning') {
                        shifts = ['A', 'General'];
                    } else if (time === 'afternoon') {
                        shifts = ['B', 'C'];
                    }
                }

                shiftElement.empty().append('<option value="" disabled selected>Select Shift(s)</option>');
                shifts.forEach(function (shift) {
                    shiftElement.append($('<option>', { value: shift, text: shift }));
                });

                // Fetch and update menu options based on distribution type
                $.ajax({
                    url: '/get-details',
                    data: { distribution_type: type },
                    success: function (data) {
                        menuElement.empty().append('<option value="" disabled selected>Select Menu(s)</option>');
                        data.menus.forEach(function (menu) {
                            menuElement.append($('<option>', { value: menu.id, text: menu.name }));
                        });
                    },
                    error: function () {
                        menuElement.empty().append('<option>Error loading menus</option>');
                    }
                });
            }

            distributionTypeElement.change(updateShiftsAndMenu);
            timeOfDayElement.change(updateShiftsAndMenu);
        });
    </script>
@endsection
