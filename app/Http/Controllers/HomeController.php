<?php

namespace OverSurgery\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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
}
