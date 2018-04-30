<?php

namespace OverSurgery\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OverSurgery\User;

class HomeController extends Controller
{
  /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function getUserChatDetails()
    {
        $IsReceptionist = Auth::user()->hasRole('receptionist');
        return [Auth::user()->id,$IsReceptionist ? "rec"  : ""];
    }

    public function getUser($id)
    {
        return User::query()->find($id)->name;
    }

    public function availableStaff(){

        $carbon = Carbon::now();
        $dayId = $carbon->format('w');

        $staff = DB::table('users')->select('users.id','users.name','role_id')
            ->join('user_shift_days','user_shift_days.user_id','=','users.id')
            ->join('role_user','role_user.user_id','=','users.id')
            ->where("role_user.role_id", ">=", "3")->where("user_shift_days.shift_day_id",'=',$dayId)
            ->get();



        return view('staff')->with("staff",$staff);
    }

    public function availableStaffChange($day){

        $carbon = new Carbon($day);
        $dayId = $carbon->format('w');

        $staff = DB::table('users')->select('users.id','users.name','role_id')
            ->join('user_shift_days','user_shift_days.user_id','=','users.id')
            ->join('role_user','role_user.user_id','=','users.id')
            ->where("role_user.role_id", ">=", "3")->where("user_shift_days.shift_day_id",'=',$dayId)
            ->get();

        return view('_staffTable')->with("staff",$staff);
    }
}
