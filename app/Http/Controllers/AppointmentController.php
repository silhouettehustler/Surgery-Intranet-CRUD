<?php

namespace OverSurgery\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        //TODO disable deleted records to be pulled from here
        $appointments = Appointment::all()->where("user_id","=",Auth::user()->id)
            ->where("cancelled","=",0);

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

        $gps =  DB::table('users')->select('users.id','users.name')
            ->join('assigned_user_types','assigned_user_types.user_id','=','users.id')
            ->where("assigned_user_types.user_type_id", "=", "1")
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
        //
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
        if ($appointment->user_id != Auth::user()->id){
            return response('Attempting to delete an appointment for a different user!', 401);
        }

        $gps = DB::table('users')->select('users.id','users.name')
            ->join('assigned_user_types','assigned_user_types.user_id','=','users.id')
            ->where("assigned_user_types.user_type_id", "=", "1")
            ->get();

        return View("_appointment",compact("appointment","gps"));
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
    public function timeSlots($id){

        $user = User::all()->find($id);
        $shift = Shift::all()->find($user->shift_id);
        $appointments = Appointment::all()->where("employee_id","=",$id);
        $startTime = $shift->start_time;
        $endTime = $shift->end_time;
        $options = array();
        $cls = "";
        for( $i=$startTime; $i<$endTime; $i+=30) {

            if ($appointments->contains("datetime","=",$startTime)){
                $cls = "disabled";
            }
            array_push($options, "<option class='{{$cls}}' value='{{$i}}'>{{$i("l - H:i",$i)}}</option>");
        }

        return $options;
    }
}
