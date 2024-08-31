@extends('Backend.master')

@section('content')
    <div class="container mt-5">
        <div class="card border-light shadow-sm">
            <div class="card-body">
                <h1 class="card-title text-center mb-4">Edit Distribution Record</h1>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('distribution.update', $distribution->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4">
                        <label for="distribution_type" class="font-weight-bold">Distribution Type:</label>
                        <select id="distribution_type" name="distribution_type" class="form-control form-control-lg" required>
                            <option value="" disabled>Select Distribution Type</option>
                            <option value="lunch" {{ $distribution->distribution_type === 'lunch' ? 'selected' : '' }}>Lunch</option>
                            <option value="snack" {{ $distribution->distribution_type === 'snack' ? 'selected' : '' }}>Snack</option>
                        </select>
                    </div>

                    <div class="form-group mb-4" id="time_of_day_container" style="{{ $distribution->distribution_type === 'snack' ? 'display: block;' : 'display: none;' }}">
                        <label for="time_of_day" class="font-weight-bold">Time of Day (for Snacks):</label>
                        <select id="time_of_day" name="time_of_day" class="form-control form-control-lg">
                            <option value="" disabled>Select Time of Day</option>
                            <option value="morning" {{ $distribution->time_of_day === 'morning' ? 'selected' : '' }}>Morning</option>
                            <option value="afternoon" {{ $distribution->time_of_day === 'afternoon' ? 'selected' : '' }}>Afternoon</option>
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
                            <!-- Options will be added dynamically -->
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="day" class="font-weight-bold">Day(s):</label>
                        <select id="day" name="day[]" class="form-control form-control-lg" multiple required>
                            <option value="saturday" {{ in_array('saturday', $distribution->days ?? []) ? 'selected' : '' }}>Saturday</option>
                            <option value="sunday" {{ in_array('sunday', $distribution->days ?? []) ? 'selected' : '' }}>Sunday</option>
                            <option value="monday" {{ in_array('monday', $distribution->days ?? []) ? 'selected' : '' }}>Monday</option>
                            <option value="tuesday" {{ in_array('tuesday', $distribution->days ?? []) ? 'selected' : '' }}>Tuesday</option>
                            <option value="wednesday" {{ in_array('wednesday', $distribution->days ?? []) ? 'selected' : '' }}>Wednesday</option>
                            <option value="thursday" {{ in_array('thursday', $distribution->days ?? []) ? 'selected' : '' }}>Thursday</option>
                            <option value="friday" {{ in_array('friday', $distribution->days ?? []) ? 'selected' : '' }}>Friday</option>
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg px-4 py-2">Update Distribution Record</button>
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
            const distributionTypeElement = $('#distribution_type');
            const timeOfDayElement = $('#time_of_day');
            const timeOfDayContainer = $('#time_of_day_container');
            const shiftElement = $('#shift');
            const menuElement = $('#menu');
            const dayElement = $('#day');

            function updateForm() {
                const type = distributionTypeElement.val();
                const time = timeOfDayElement.val();

                let shifts = [];
                let menuUrl = '/get-details';

                if (type === 'lunch') {
                    shifts = ['General', 'A', 'B'];
                    timeOfDayContainer.hide();
                } else if (type === 'snack') {
                    timeOfDayContainer.show();
                    menuUrl = '/get-menus';
                    shifts = time === 'morning' ? ['A', 'General'] : ['B', 'C'];
                }

                shiftElement.empty().append('<option value="" disabled>Select Shift(s)</option>');
                shifts.forEach(shift => shiftElement.append(new Option(shift, shift)));

                // Fetch and update menu options based on distribution type
                $.ajax({
                    url: menuUrl,
                    data: { distribution_type: type },
                    success: function (data) {
                        menuElement.empty().append('<option value="" disabled>Select Menu(s)</option>');
                        data.menus.forEach(menu => menuElement.append(new Option(menu.name, menu.id)));
                    },
                    error: function () {
                        menuElement.empty().append('<option>Error loading menus</option>');
                    }
                });
            }

            distributionTypeElement.change(updateForm);
            timeOfDayElement.change(updateForm);

            // Initial setup
            updateForm();
        });
    </script>
@endsection
