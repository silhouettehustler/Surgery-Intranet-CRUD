<?php

namespace OverSurgery\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return View("appointment");
    }
}
