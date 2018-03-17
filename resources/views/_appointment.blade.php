
<form class="form-horizontal" role="form">

    <input type="hidden" name="id" value="{{$appointment->id}}" class="form-control">

    <div class="form-group">
        <label for="gp_id" class="col-lg-2 control-label">GP</label>
        <div class="col-lg-10">
            <select class="form-control" name="gp_id" id="gp_id">

                @foreach($gps as $gp)
                    <option value="{{ $gp->id }}" {{ $appointment->employee_id == $gp->id ? 'selected="selected"' : '' }}>{{ $gp->name }}</option>
                @endforeach

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
        <label for="datetime" class="col-lg-2 control-label">Date/Time</label>
        <div class="col-lg-10">
            <input type="text" name="datetime" id="datetime" value="{{$appointment->datetime}}" class="form-control datepicker">
        </div>
    </div>

</form>