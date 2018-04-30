
    @foreach($staff as $employee)
        <tr>
            <td>{{$employee->name}}</td>
            <td>{{$employee->role_id == 3 ? "GP" :"Nurse"}}</td>
        </tr>
    @endforeach