@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Edit Distribution Record</h1>

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

        <!-- Display Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('distribution.update', $distribution->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="distribution_type" class="font-weight-bold">Distribution Type:</label>
                <select id="distribution_type" name="distribution_type" class="form-control" required>
                    <option value="">Select Distribution Type</option>
                    <option value="lunch" {{ $distribution->distribution_type === 'lunch' ? 'selected' : '' }}>Lunch</option>
                    <option value="snack" {{ $distribution->distribution_type === 'snack' ? 'selected' : '' }}>Snack</option>
                </select>
            </div>

            <div class="form-group" id="time_of_day_container" style="{{ $distribution->distribution_type === 'snack' ? '' : 'display: none;' }}">
                <label for="time_of_day" class="font-weight-bold">Time of Day (for Snacks):</label>
                <select id="time_of_day" name="time_of_day" class="form-control">
                    <option value="">Select Time of Day</option>
                    <option value="morning" {{ $distribution->time_of_day === 'morning' ? 'selected' : '' }}>Morning</option>
                    <option value="afternoon" {{ $distribution->time_of_day === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shift" class="font-weight-bold">Shift(s):</label>
                <select id="shift" name="shift[]" class="form-control" multiple required>
                    <!-- Options will be added dynamically -->
                </select>
            </div>

            <div class="form-group">
                <label for="menu" class="font-weight-bold">Menu(s):</label>
                <select id="menu" name="menu_id[]" class="form-control" multiple required>
                    <!-- Options will be added dynamically -->
                </select>
            </div>

            <div class="form-group">
                <label for="day" class="font-weight-bold">Day(s):</label>
                <select id="day" name="day[]" class="form-control" multiple required>
                    <option value="regular" {{ in_array('regular', $days) ? 'selected' : '' }}>Regular</option>
                    <option value="saturday" {{ in_array('saturday', $days) ? 'selected' : '' }}>Saturday</option>
                    <option value="sunday" {{ in_array('sunday', $days) ? 'selected' : '' }}>Sunday</option>
                    <option value="monday" {{ in_array('monday', $days) ? 'selected' : '' }}>Monday</option>
                    <option value="tuesday" {{ in_array('tuesday', $days) ? 'selected' : '' }}>Tuesday</option>
                    <option value="wednesday" {{ in_array('wednesday', $days) ? 'selected' : '' }}>Wednesday</option>
                    <option value="thursday" {{ in_array('thursday', $days) ? 'selected' : '' }}>Thursday</option>
                    <option value="friday" {{ in_array('friday', $days) ? 'selected' : '' }}>Friday</option>
                </select>
            </div>

            <div class="form-group d-flex justify-content-center" style="padding:10px;">
                <button type="submit" class="btn btn-primary btn-lg">Update</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
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

                shiftElement.empty().append('<option value="">Select Shift(s)</option>');
                shifts.forEach(function (shift) {
                    shiftElement.append($('<option>', { value: shift, text: shift }));
                });

                // Fetch and update menu options based on distribution type
                $.ajax({
                    url: '/get-details',
                    data: { distribution_type: type },
                    success: function (data) {
                        menuElement.empty().append('<option value="">Select Menu</option>');
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

            // Initialize form with existing data
            updateShiftsAndMenu();
        });
    </script>
@endsection
