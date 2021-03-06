<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use notifications;

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
            $notification = Auth::user()->unreadNotifications;
            return view('dashboard',compact('notification'));

    }
    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadnotifications
            ->when($request->input('id'),function($query) use ($request){
                return $query->where('id',$request->input('id'));
            })
            ->markAsRead();
        return response()->noContent();
    }

}
