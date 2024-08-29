@extends('Backend.Master')

@section('content')
<div class="pagetitle">
    <h1>Welcome to the Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dashboard Overview</h5>
                    
                    <p class="card-text">Welcome to the admin dashboard. Here you can manage all your data, view analytics, and access various sections of the application.</p>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Employees</h5>
                                    <p class="card-text">Manage employee records and details.</p>
                                    <a href="{" class="btn btn-light">View Employees</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Departments</h5>
                                    <p class="card-text">Manage department information.</p>
                                    <a href="" class="btn btn-light">View Departments</a>
                                </div>
                            </div>
                        </div>
                    
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
