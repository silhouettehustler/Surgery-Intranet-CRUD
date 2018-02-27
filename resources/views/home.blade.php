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
                        <div class="col-md-12" style="display: -webkit-box;text-align: center;">
                            <div class="col-md-4">
                                <img class="dashboard-card-img col-md-12" src="{{asset('images/appointment-img.jpeg')}}" alt="Appointments">
                                <i class="col-md-12">APPOINTMENTS</i>
                            </div>
                            <div class="col-md-4">
                                <img class="dashboard-card-img col-md-12" src="{{asset('images/results-img.jpeg')}}" alt="Results">
                                <i class="col-md-12">RESULTS</i>
                            </div>
                            <div class="col-md-4">
                                <img class="dashboard-card-img col-md-12" src="{{asset('images/help-img.jpeg')}}" alt="Online Chat">
                                <i class="col-md-12">ONLINE CHAT</i>
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

<style>
    .dashboard-card-img{
        border: 1px solid #848496;
        cursor: pointer;
        background: #d5fff1
    }
    .dashboard-card-img:hover{
        background-color: #8897ff;
    }
</style>
