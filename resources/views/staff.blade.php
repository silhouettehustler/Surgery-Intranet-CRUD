@extends('layouts.app')
@section('title', 'Available Staff')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Available Staff</div>

                    <div class="card-body">

                        <div class="form-group">
                            <div class="col-lg-3">
                                <input onchange="Main.Staff.DateFilter(this)" type="text" name="date" placeholder="Select Date..." class="form-control datepicker">
                            </div>
                        </div>

                        <table class="sortable-table" width="100%" id="staff-planer-table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($staff as $employee)
                                <tr>
                                    <td>{{$employee->name}}</td>
                                    <td>{{$employee->role_id == 3 ? "GP" :"Nurse"}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
