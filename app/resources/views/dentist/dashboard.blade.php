@extends('layouts.app')

@section('title', 'Dentist Dashboard - ShinyTooth')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h1>Welcome, Dentist!</h1>
            <p class="lead">Manage your patients and appointments.</p>

            <div class="row mt-4">
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
                            <h5 class="card-title">My Patients</h5>
                            <p class="card-text">Manage your patient list and records.</p>
                            <a href="#" class="btn btn-primary">View Patients</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Services</h5>
                            <p class="card-text">Manage your dental services.</p>
                            <a href="#" class="btn btn-primary">Manage Services</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Specializations</h5>
                            <p class="card-text">Update your specializations.</p>
                            <a href="#" class="btn btn-primary">Update Specializations</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Reports</h5>
                            <p class="card-text">View your performance reports.</p>
                            <a href="#" class="btn btn-primary">View Reports</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Ratings & Reviews</h5>
                            <p class="card-text">Check your patient ratings and reviews.</p>
                            <a href="#" class="btn btn-primary">View Ratings</a>
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
                    <p><strong>Name:</strong> <span id="dentist-name">Loading...</span></p>
                    <p><strong>Email:</strong> <span id="dentist-email">Loading...</span></p>
                    <p><strong>Phone:</strong> <span id="dentist-phone">Loading...</span></p>
                    <p><strong>License:</strong> <span id="dentist-license">N/A</span></p>
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
            document.getElementById('dentist-name').textContent = userData.name || 'N/A';
            document.getElementById('dentist-email').textContent = userData.email || 'N/A';
            document.getElementById('dentist-phone').textContent = userData.phone || 'N/A';
        }
    });
</script>
@endsection
