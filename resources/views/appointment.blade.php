@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Appointments</div>

                    <div class="card-body">
                        <div class="col-md-12" style="margin-bottom: 15px;">
                            <button class="btn btn-outline-success modal-container-trigger" data-url="{{ route('appointment-create') }}">Book Appointment</button>
                        </div>

                        @if ($appointments->count() > 0)
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>GP</th>
                                    <th>Description</th>
                                    <th>Date/Time</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{\OverSurgery\User::all()->find($appointment -> employee_id)->name}}</td>
                                        <td>{{$appointment->description}}</td>
                                        <td>{{$appointment->datetime}}</td>
                                        <td>
                                            <button class="btn btn-outline-primary modal-container-trigger" data-url="{{ route('appointment-edit',$appointment->id) }}">Edit</button>
                                            <button class="btn btn-outline-danger" onclick="Main.Appointments.Destroy(this)" data-url="{{ route('appointment-destroy',$appointment->id) }}">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <i>No appointments found</i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
