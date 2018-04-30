@extends('layouts.app')
@section('title', 'Prescriptions')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Prescriptions</div>

                    <div class="card-body">
                        <table class="sortable-table" width="100%">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th>Expiry Date</th>
                                <th>Extendable</th>
                                <th>Date Extended</th>
                                <th class="no-sort"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($prescriptions as $prescription)
                                <tr>
                                    <td>{{$prescription->description}}</td>
                                    <td>{{$prescription->expiry_date}}</td>
                                    <td>{{$prescription->can_extend == 1 ? "yes" : "no"}}</td>
                                    <td>{{$prescription->extended_date}}</td>

                                        <td>
                                            @if ($prescription->can_extend == true && $prescription->extended_date == null)
                                            <button class="btn btn-sm btn-outline-primary" onclick="Main.Prescriptions.Extend(this)" data-url="{{ route('prescription-extend',$prescription->id) }}">Extend <i class="fa fa-cog"></i></button>
                                            @endif
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
