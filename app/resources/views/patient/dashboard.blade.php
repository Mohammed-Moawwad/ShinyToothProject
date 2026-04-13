@extends('layouts.app')

@section('title', 'Patient Dashboard - ShinyTooth')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h1>Welcome, Patient!</h1>
            <p class="lead">Manage your dental appointments and health records.</p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Book Appointment</h5>
                            <p class="card-text">Schedule your next dental visit.</p>
                            <a href="#" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">My Appointments</h5>
                            <p class="card-text">View and manage your scheduled appointments.</p>
                            <a href="#" class="btn btn-primary">View Appointments</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">My Records</h5>
                            <p class="card-text">Access your dental health records.</p>
                            <a href="#" class="btn btn-primary">View Records</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Payments</h5>
                            <p class="card-text">View and manage your payments.</p>
                            <a href="#" class="btn btn-primary">View Payments</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Profile</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> <span id="patient-name">Loading...</span></p>
                    <p><strong>Email:</strong> <span id="patient-email">Loading...</span></p>
                    <p><strong>Phone:</strong> <span id="patient-phone">Loading...</span></p>
                    <button class="btn btn-secondary w-100 mt-3" onclick="logout()">Logout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/auth.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userData = getUserData();
        if (userData) {
            document.getElementById('patient-name').textContent = userData.name || 'N/A';
            document.getElementById('patient-email').textContent = userData.email || 'N/A';
            document.getElementById('patient-phone').textContent = userData.phone || 'N/A';
        }
    });
</script>
@endsection
