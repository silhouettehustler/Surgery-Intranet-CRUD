
<form class="form-horizontal" role="form" id="appointment-form" data-url="{{route('appointment-store')}}">

    <input type="hidden" name="id" value="{{$appointment->id}}" class="form-control">
    <input type="hidden" name="user_id" value="{{$appointment->user_id}}">

    <div class="form-group">
        <label for="datetime" class="col-lg-2 control-label">Date</label>
        <div class="col-lg-10">
            <input type="text" name="date" id="date" value="{{$appointment->date}}" class="form-control datepicker">
        </div>
    </div>

    <div class="form-group">
        <label for="gp_id" class="col-lg-2 control-label">GP</label>
        <div class="col-lg-10">
            <select class="form-control" name="employee_id" id="employee_id" value="{{$appointment->employee_id}}">
                @if ($appointment->employee_id != null)
                    @foreach($gps as $gp)
                        <option value="{{ $gp->id }}" {{ $appointment->employee_id == $gp->id ? 'selected="selected"' : '' }}>{{ $gp->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="description" class="col-lg-2 control-label">Description</label>
        <div class="col-lg-10">
            <input type="text" name="description" id="description" value="{{$appointment->description}}" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="time_slot" class="col-lg-4 control-label">Time Slot</label>
        <div class="col-lg-10">
            <select class="form-control" name="start_time" id="start_time" value="{{$appointment->start_time}}">
                @if ($appointment->start_time != null)
                    @foreach($timeslots as $timeslot)
                        <option value="{{ $timeslot }}" {{ $appointment->start_time == $timeslot ? 'selected="selected"' : '' }}>{{ $timeslot }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>

</form>