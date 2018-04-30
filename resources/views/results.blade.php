@extends('layouts.app')
@section('title', 'Results')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Results</div>

                    <div class="card-body">

                        <table class="sortable-table" width="100%">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Blood Pressure</th>
                                <th>Blood Sugar</th>
                                <th>Albumin</th>
                                <th>Bilirubin</th>
                                <th>Calcium</th>
                                <th>Chloride</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td>{{$result->date}}</td>
                                    <td>{{$result->blood_pressure}}</td>
                                    <td>{{$result->blood_sugar}}</td>
                                    <td>{{$result->albumin}}</td>
                                    <td>{{$result->bilirubin}}</td>
                                    <td>{{$result->calcium}}</td>
                                    <td>{{$result->chloride}}</td>
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
