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
                        <div class="col-md-12 col-sm-1" style="text-align: center;">
                            <div class="btn-group">
                                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('receptionist') || \Illuminate\Support\Facades\Auth::user()->hasRole('gp'))
                                    <button onclick="location.href='appointment'" class="btn btn-outline-primary">Manage Appointments</button>
                                @else
                                    <ul style="list-style-type: none; margin-right: 20px;">
                                        <li><button class="btn btn-outline-dark home-panel-list">My Prescriptions</button></li>
                                        <li><button onclick="location.href='appointment'" class="btn btn-outline-primary home-panel-list">My Appointments</button></li>
                                        <li><button class="btn btn-outline-warning home-panel-list">Staff Planner</button></li>
                                        <li><button onclick="location.href='results'" class="btn btn-outline-danger home-panel-list">My Results</button></li>
                                    </ul>

                                @endif
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
