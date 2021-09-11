<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->hasRole('hrd'))
        {
            $notification = Auth::user()->unreadNotifications;
            return view('dashboard',compact('notification'));
        }else{
            $notification = Auth::user()->unreadNotifications;
            return view('dashboard',compact('notification'));
        }


        // dd($name);

    }
}
