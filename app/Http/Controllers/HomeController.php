<?php

namespace App\Http\Controllers;

use App\Models\HumberSetting;
use App\Models\Jobcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $user = Auth::user();

        if ($user->hasRole('admin'))
        {
            $settings = HumberSetting::where('id',1)->first();

            $food_count = 0;
            $jobcards = Jobcard::all();

            foreach ($jobcards as $job)
            {
                if ($job->remaining > 0 && $job->card_type)
                {
                    $food_count += $job->remaining;
                }
            }

            $jobcards = DB::table('jobcards')->orderBy('created_at','desc')->limit(5)->get();

            return view('home',compact('settings','jobcards','food_count'));
        }
    }
}
