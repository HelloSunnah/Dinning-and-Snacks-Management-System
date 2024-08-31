@extends('Backend.master')

@section('content')
    <div class="main-content">
        <h1>Dashboard</h1>

        <!-- Summary Statistics -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Menus</h4>
                    </div>
                    <div class="card-body">
                        <p>{{ $menuCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Manpower</h4>
                    </div>
                    <div class="card-body">
                        <p>{{ $manpowerCount }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Distributions</h4>
                    </div>
                    <div class="card-body">
                        <p>{{ $distributionCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Recent Menus</h3>
                <ul class="list-group">
                    @foreach($recentMenus as $menu)
                    @if($menu->unit=='kg')

                    <li class="list-group-item">{{ $menu->name }} ({{ $menu->quantity_per_person/1000 }} {{ $menu->unit }})</li>
                    @else
                    <li class="list-group-item">{{ $menu->name }} ({{ $menu->quantity_per_person }} {{ $menu->unit }})</li>                   
                    @endif
                    @endforeach
                </ul>
            </div>
            <div class="col-md-6">
                <h3>Recent Manpower</h3>
                <ul class="list-group">
                    @foreach($recentManpower as $manpower)
                        <li class="list-group-item">{{ $manpower->shift }} - {{ $manpower->member }} members</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Recent Distributions</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Shift</th>
                            <th>Day</th>
                            <th>Time of Day</th>
                            <th>Menu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDistributions as $distribution)
                            <tr>
                                <td>{{ $distribution->id }}</td>
                                <td>{{ ucfirst($distribution->distribution_type) }}</td>
                                <td>{{ strtoupper($distribution->shift) }}</td>
                                <td>{{ ucfirst($distribution->day) }}</td>
                                <td>{{ ucfirst($distribution->time_of_day) }}</td>
                                <td>{{ ucfirst($distribution->menu->name) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Distribution Types</h3>
                <canvas id="distributionChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            var ctx = document.getElementById('distributionChart').getContext('2d');
            var distributionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Lunch', 'Snack'],
                    datasets: [{
                        label: 'Distribution Types',
                        data: [{{ $lunchCount }}, {{ $snackCount }}],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
