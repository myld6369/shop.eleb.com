<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    //
    Public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['']
        ]);
    }
    public function index()
    {
        $time =time();
        $activities =Activity::where('end_time','>',$time)->paginate(5);
        return view('Activity.index',compact('activities'));
    }

    public function show(Activity $activity)
    {
        return view('Activity/show',compact('activity'));
    }
}
