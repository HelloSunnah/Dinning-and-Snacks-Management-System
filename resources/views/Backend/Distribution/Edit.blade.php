@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Edit Distribution Record</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('distribution.update', $distribution->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="distribution_type" class="font-weight-bold">Distribution Type:</label>
                <select id="distribution_type" name="distribution_type" class="form-control" required>
                    <option value="lunch" {{ $distribution->distribution_type == 'lunch' ? 'selected' : '' }}>Lunch</option>
                    <option value="snack" {{ $distribution->distribution_type == 'snack' ? 'selected' : '' }}>Snack</option>
                </select>
            </div>

            <div class="form-group" id="time_of_day_container" style="{{ $distribution->distribution_type == 'snack' ? '' : 'display: none;' }}">
                <label for="time_of_day" class="font-weight-bold">Time of Day (for Snacks):</label>
                <select id="time_of_day" name="time_of_day" class="form-control">
                    <option value="morning" {{ $distribution->time_of_day == 'morning' ? 'selected' : '' }}>Morning</option>
                    <option value="afternoon" {{ $distribution->time_of_day == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                </select>
            </div>

            <div class="form-group">
                <label for="shift" class="font-weight-bold">Shift:</label>
                <select id="shift" name="shift" class="form-control" required>
                    <option value="{{ $distribution->shift }}">{{ $distribution->shift }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="menu" class="font-weight-bold">Menu:</label>
                <select id="menu" name="menu_id" class="form-control" required>
                    <option value="{{ $distribution->menu_id }}">{{ $distribution->menu->name }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="day" class="font-weight-bold">Day:</label>
                <select id="day" name="day" class="form-control" required>
                    <option value="regular" {{ $distribution->day == 'regular' ? 'selected' : '' }}>Regular</option>
                    <option value="saturday" {{ $distribution->day == 'saturday' ? 'selected' : '' }}>Saturday</option>
                    <option value="sunday" {{ $distribution->day == 'sunday' ? 'selected' : '' }}>Sunday</option>
                    <option value="monday" {{ $distribution->day == 'monday' ? 'selected' : '' }}>Monday</option>
                    <option value="tuesday" {{ $distribution->day == 'tuesday' ? 'selected' : '' }}>Tuesday</option>
                    <option value="wednesday" {{ $distribution->day == 'wednesday' ? 'selected' : '' }}>Wednesday</option>
                    <option value="thursday" {{ $distribution->day == 'thursday' ? 'selected' : '' }}>Thursday</option>
                    <option value="friday" {{ $distribution->day == 'friday' ? 'selected' : '' }}>Friday</option>
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

                shiftElement.empty().append('<option value="">Select Shift</option>');
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
        });
    </script>
@endsection
