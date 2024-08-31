@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Prediction</h1>

        <!-- Prediction Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Prediction Form</h5>
            </div>
            <div class="card-body">
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

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Calculate</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Section -->
        <div id="results" class="mt-4" style="display: none;">
            <!-- Shifts Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Shifts Receiving <span id="distributionTypeLabel"></span></h5>
                </div>
                <div class="card-body">
                    <p>Total Number of Members: <span id="totalMembers">0</span></p>
                    <ul id="shiftsList" class="list-group"></ul>
                </div>
            </div>

            <!-- Menu Items Needed -->
            <div id="menuItems">
                <!-- Morning Snacks -->
                <div id="morningBlock" class="card mb-4" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0"> Morning Snacks</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="morningMenuItems"></div>
                    </div>
                </div>

                <!-- Afternoon Snacks -->
                <div id="afternoonBlock" class="card mb-4" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0"> Afternoon Snacks</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="afternoonMenuItems"></div>
                    </div>
                </div>

                <!-- Lunch Menu Items -->
                <div id="lunchBlock" class="card mb-4" style="display: none;">
                    <div class="card-header">
                        <h5 class="mb-0">Lunch Menu Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="lunchMenuItems"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
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
                                $('#shiftsList').append(`<li class="list-group-item">${shift.shift}: ${shift.member} members</li>`);
                                totalMembers += shift.member; // Calculate total members
                            });

                            // Populate morning menu items
                            $('#morningMenuItems').empty();
                            response.morning_menu_items.forEach(function (item) {
                                $('#morningMenuItems').append(`
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">${item.menu_name}</h5>
                                                <p class="card-text">${item.quantity} ${item.unit}</p>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            });

                            // Populate afternoon menu items
                            $('#afternoonMenuItems').empty();
                            response.afternoon_menu_items.forEach(function (item) {
                                $('#afternoonMenuItems').append(`
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">${item.menu_name}</h5>
                                                <p class="card-text">${item.quantity} ${item.unit}</p>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            });

                        } else if (distributionType === 'lunch') {
                            $('#morningBlock').hide();
                            $('#afternoonBlock').hide();
                            $('#lunchBlock').show();

                            // Populate lunch menu items
                            $('#lunchMenuItems').empty();
                            response.menu_items.forEach(function (item) {
                                $('#lunchMenuItems').append(`
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">${item.menu_name}</h5>
                                                <p class="card-text">${item.quantity} ${item.unit}</p>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            });

                            // Populate shifts list for lunch and calculate total members
                            $('#shiftsList').empty();
                            response.unique_shifts.forEach(function (shift) {
                                $('#shiftsList').append(`<li class="list-group-item">${shift.shift}: ${shift.member} members</li>`);
                                totalMembers += shift.member; // Calculate total members
                            });
                        }

                        // Display total members
                        $('#totalMembers').text(totalMembers);
                    },
                    error: function (xhr) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
