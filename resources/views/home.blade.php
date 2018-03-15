@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @else
                    <div class="row">
                        <div class="col-md-12 col-sm-1">
                            <div class="btn-group">
                                <button class="btn btn-outline-dark">My Prescriptions/Medications</button>
                                <button onclick="location.href='appointment'" class="btn btn-outline-primary">My Appointments</button>
                                <button class="btn btn-outline-warning">Staff Planner</button>
                                <button class="btn btn-outline-danger">My Results</button>
                                <button class="btn btn-outline-success">Chat</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
