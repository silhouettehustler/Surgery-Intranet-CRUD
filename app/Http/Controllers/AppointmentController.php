<?php

namespace OverSurgery\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use OverSurgery\Appointment;
use OverSurgery\Shift;
use OverSurgery\User;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = null;

        if (Auth::user()->hasRole('receptionist') || Auth::user()->hasRole('gp')){

            $appointments = Appointment::all()->where("cancelled","=",0);
        }else{

            $appointments = Appointment::all()->where("user_id","=",Auth::user()->id)
                ->where("cancelled","=",0);
        }

        return view('appointment')->with("appointments",$appointments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $appointment = new Appointment();
        $appointment->user_id = Auth::user()->id;

        $gps = DB::table('users')->select('users.id','users.name')
            ->join('role_user','role_user.user_id','=','users.id')
            ->where("role_user.role_id", "=", "3")
            ->get();

        return View("_appointment",compact("appointment","gps"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request...

        $appointment = new Appointment();

        $appointment->date = date("Y-m-d",strtotime($request->date));
        $appointment->start_time = date("H:i:s", strtotime($request->start_time));
        $appointment->end_time =  date("H:i:s", strtotime('+30 minutes', strtotime($request->start_time)));
        $appointment->description = $request->description;
        $appointment->user_id = $request->user_id;
        $appointment->employee_id = $request->employee_id;

        $appointment->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //find appointment with passed id
        $appointment = Appointment::query()->find($id);

        //check if user is attempting to delete own appointment

        if (!Auth::user()->hasRole('receptionist')){

            if ($appointment->user_id != Auth::user()->id){
                return response('Attempting to delete an appointment for a different user!', 401);
            }
        }

        $carbon = new Carbon($appointment->date);
        $dayId = $carbon->format('w');

        $gps = DB::table('users')->select('users.id','users.name')
            ->join('user_shift_days','user_shift_days.user_id','=','users.id')
            ->join('role_user','role_user.user_id','=','users.id')
            ->where("role_user.role_id", "=", "3")->where("user_shift_days.shift_day_id",'=',$dayId)
            ->get();



        $user = User::all()->find($appointment->employee_id);
        $shift = Shift::all()->find($user->shift_id);
        $appointments = Appointment::all()->where("employee_id","=",$id)->where('date','=',date("Y-m-d",strtotime($appointment->date)));
        $startTime = $shift->start_time;
        $endTime = $shift->end_time;
        $timeslots = array();
        $start_time    = strtotime ($startTime); //change to strtotime
        $end_time      = strtotime ($endTime); //change to strtotime
        $add_mins  = 30 * 60;

        while ($start_time <= $end_time) // loop between time
        {
            if (!$appointments->contains("start_time","=",date("H:i:s",$start_time))){
                $t = date("H:i:s",$start_time);
                array_push($timeslots, "$t");
            }

            $start_time += $add_mins; // to check endtie=me
        }

        return View("_appointment",compact("appointment","gps","timeslots"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        //find appointment with passed id
        $appointment = Appointment::query()->find($id);

        //check if user is attempting to delete own appointment
        if ($appointment->user_id != Auth::user()->id) {
            return response('Attempting to delete an appointment for a different user!', 401);
        }

        //delete appointment
        $appointment->cancelled = 1;
        $appointment->save();
        return response('Appointment successfully deleted!', 200);
    }

    //takes user id (GP id)
    public function timeSlots($id,$date){

        $user = User::all()->find($id);
        $shift = Shift::all()->find($user->shift_id);
        $appointments = Appointment::all()->where("employee_id","=",$id)->where('date','=',date("Y-m-d",strtotime($date)));
        $startTime = $shift->start_time;
        $endTime = $shift->end_time;
        $options = array();
        $start_time    = strtotime ($startTime); //change to strtotime
        $end_time      = strtotime ($endTime); //change to strtotime
        $add_mins  = 30 * 60;

        while ($start_time <= $end_time) // loop between time
        {
            if (!$appointments->contains("start_time","=",date("H:i:s",$start_time))){
                $t = date("H:i:s",$start_time);
                array_push($options, "<option value=$t>$t</option>");
            }

            $start_time += $add_mins; // to check endtie=me
        }

        return $options;
    }

    /**
     * @param $day
     * @return mixed
     */
    public function getAvailableStaff($day){

        $carbon = new Carbon($day);
        $dayId = $carbon->format('w');


        $gps = DB::table('users')->select('users.id','users.name')
            ->join('user_shift_days','user_shift_days.user_id','=','users.id')
            ->join('role_user','role_user.user_id','=','users.id')
            ->where("role_user.role_id", "=", "3")->where("user_shift_days.shift_day_id",'=',$dayId)
            ->get();

        $options = array();

        foreach($gps as $gp){
            array_push($options,"<option value=$gp->id>$gp->name</option>");
        }

        return $options;
    }
}
