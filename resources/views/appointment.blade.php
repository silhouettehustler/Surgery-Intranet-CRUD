@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Appointments</div>

                    <div class="card-body">
                        <div class="col-md-12" style="margin-bottom: 15px;">
                            <button class="btn btn-sm btn-outline-success modal-container-trigger" data-title="Book Appointment" data-url="{{ route('appointment-create') }}">
                                Book Appointment <i class="fa fa-plus"></i></button>
                        </div>

                            <table class="sortable-table" width="100%">
                                <thead>
                                <tr>
                                    <th>GP</th>
                                    <th>Description</th>
                                    <th>Date/Time</th>
                                    <th class="no-sort"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($appointments as $appointment)
                                    <tr>
                                        <td>{{\OverSurgery\User::all()->find($appointment -> employee_id)->name}}</td>
                                        <td>{{$appointment->description}}</td>
                                        <td>{{$appointment->datetime}}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary modal-container-trigger" data-url="{{ route('appointment-edit',$appointment->id) }}" data-title="Edit Appointment">Edit <i class="fa fa-cog"></i></button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="Main.Appointments.Destroy(this)" data-url="{{ route('appointment-destroy',$appointment->id) }}">Delete <i class="fa fa-ban"></i></button>
                                        </td>
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
