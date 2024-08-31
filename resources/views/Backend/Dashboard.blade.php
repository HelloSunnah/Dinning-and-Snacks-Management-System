@extends('Backend.master')

@section('content')
    <div class="main-content">
        <div class="container-fluid">
            <!-- Dashboard Title -->
            <h1 class="mb-4">Dashboard</h1>

            <!-- Summary Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-light">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Menus</h5>
                            <p class="card-text display-4 font-weight-bold">{{ $menuCount }}</p>
                        </div>
                        <div class="card-footer text-muted text-center">
                            <small>Number of available menus</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-light">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Manpower</h5>
                            <p class="card-text display-4 font-weight-bold">{{ $manpowerCount }}</p>
                        </div>
                        <div class="card-footer text-muted text-center">
                            <small>Total manpower available</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-light">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Distributions</h5>
                            <p class="card-text display-4 font-weight-bold">{{ $distributionCount }}</p>
                        </div>
                        <div class="card-footer text-muted text-center">
                            <small>Total distribution records</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm border-light">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Recent Menus</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($recentMenus as $menu)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $menu->name }}
                                        <span class="badge badge-primary badge-pill">
                                            {{ $menu->unit == 'kg' ? $menu->quantity_per_person / 1000 . ' kg' : $menu->quantity_per_person . ' ' . $menu->unit }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm border-light">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">Recent Manpower</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($recentManpower as $manpower)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $manpower->shift }}
                                        <span class="badge badge-success badge-pill">{{ $manpower->member }} members</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Distributions -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm border-light">
                        <div class="card-header bg-warning text-white">
                            <h5 class="mb-0">Recent Distributions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
