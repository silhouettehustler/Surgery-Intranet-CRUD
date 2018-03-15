<form>
    <input type="hidden" name="id" value="{{$appointment->id}}" class="form-control">

    <select class="form-control" name="gp_id">

            @foreach($gps as $gp)
                <option value="{{ $gp->id }}" {{ $appointment->employee_id == $gp->id ? 'selected="selected"' : '' }}>{{ $gp->name }}</option>
            @endforeach

    </select>

    <input type="text" name="description" value="{{$appointment->description}}" class="form-control">
    <input type="text" name="datetime" value="{{$appointment->datetime}}" class="form-control datepicker">
</form>