@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Prediction</h1>

        <form id="predictionForm">
            @csrf
            <div class="form-group">
                <label for="distribution_type" class="font-weight-bold">Distribution Type:</label>
                <select id="distribution_type" name="distribution_type" class="form-control" required>
                    <option value="">Select Distribution Type</option>
                    <option value="lunch">Lunch</option>
                    <option value="snack">Snack</option>
                </select>
            </div>

            <div class="form-group">
                <label for="day" class="font-weight-bold">Day:</label>
                <select id="day" name="day" class="form-control" required>
                    <option value="">Select Day</option>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                    <option value="sunday">Sunday</option>
                </select>
            </div>

            <div class="form-group d-flex justify-content-center" style="padding:10px;">
                <button type="submit" class="btn btn-primary btn-lg">Calculate</button>
            </div>
        </form>

        <div id="results" style="display: none;">
            <h2>Results</h2>

            <!-- Display shifts and member details -->
            <div id="shiftsDetails">
                <h3>Shifts Receiving <span id="distributionTypeLabel"></span></h3>
                <p>Total Number of Members: <span id="totalMembers">0</span></p>
                <ul id="shiftsList"></ul>
            </div>

            <!-- Display menu items needed -->
            <div id="menuItems">
                <div id="morningBlock" style="display: none;">
                    <h4>Block A: Morning Snacks</h4>
                    <ul id="morningMenuItems"></ul>
                </div>
                <div id="afternoonBlock" style="display: none;">
                    <h4>Block B: Afternoon Snacks</h4>
                    <ul id="afternoonMenuItems"></ul>
                </div>
                <div id="lunchBlock" style="display: none;">
                    <h4>Lunch Menu Items</h4>
                    <ul id="lunchMenuItems"></ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#predictionForm').on('submit', function (e) {
                e.preventDefault();

                const distributionType = $('#distribution_type').val();
                const day = $('#day').val();

                $.ajax({
                    url: '{{ route("calculate") }}', // Ensure this route matches your route definition
                    method: 'GET',
                    data: {
                        distribution_type: distributionType,
                        day: day
                    },
                    success: function (response) {
                        $('#results').show();
                        $('#distributionTypeLabel').text(distributionType.charAt(0).toUpperCase() + distributionType.slice(1));

                        $('#shiftsList').empty();
                        let totalMembers = 0;

                        if (distributionType === 'snack') {
                            $('#morningBlock').show();
                            $('#afternoonBlock').show();
                            $('#lunchBlock').hide();

                            // Populate shifts list and calculate total members
                            response.unique_shifts.forEach(function (shift) {
                                $('#shiftsList').append(`<li>${shift.shift}: ${shift.member} members</li>`);
                                totalMembers += shift.member; // Calculate total members
                            });

                            // Populate morning menu items
                            $('#morningMenuItems').empty();
                            response.morning_menu_items.forEach(function (item) {
                                $('#morningMenuItems').append(`<li>${item.menu_name} - ${item.quantity} ${item.unit}</li>`);
                            });

                            // Populate afternoon menu items
                            $('#afternoonMenuItems').empty();
                            response.afternoon_menu_items.forEach(function (item) {
                                $('#afternoonMenuItems').append(`<li>${item.menu_name} - ${item.quantity} ${item.unit}</li>`);
                            });

                        } else if (distributionType === 'lunch') {
                            $('#morningBlock').hide();
                            $('#afternoonBlock').hide();
                            $('#lunchBlock').show();

                            // Populate lunch menu items
                            $('#lunchMenuItems').empty();
                            response.menu_items.forEach(function (item) {
                                $('#lunchMenuItems').append(`<li>${item.menu_name} - ${item.quantity} ${item.unit}</li>`);
                            });

                            // Populate shifts list for lunch and calculate total members
                            $('#shiftsList').empty();
                            response.unique_shifts.forEach(function (shift) {
                                $('#shiftsList').append(`<li>${shift.shift}: ${shift.member} members</li>`);
                                totalMembers += shift.member; // Calculate total members
                            });
                        }

                        // Display total members
                        $('#totalMembers').text(totalMembers);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
