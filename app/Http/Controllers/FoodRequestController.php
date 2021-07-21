<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\FoodRequest;
use App\Models\HumberSetting;
use App\Models\Jobcard;
use App\Models\User;
use App\Notifications\FoodRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FoodRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $frequests = FoodRequest::all();

        return view('frequests.index',compact('frequests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $settings = HumberSetting::where('id',1)->first();
        $users = User::all();
        return view('frequests.create',compact('users','settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'paynumber' => 'required',
            'department' => 'required',
            'name' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else {

            try {
                $frequest = new FoodRequest();
                $frequest->paynumber = $request->input('paynumber');
                $frequest->department = $request->input('department');
                $frequest->name = $request->input('name');

                if ($request->type == 'food' || $request->type == 'meat')
                {
                    // check if the allocation exists
                    if ($request->allocation)
                    {
                        $frequest->allocation = $request->input('allocation');
                    } else {

                        return back()->with('error','Please select allocation to proceed with the request.');
                    }

                    $frequest->type = $request->type;

                } else {

                    $frequest->type = $request->type;
                }
                $frequest->done_by = Auth::user()->full_name;
                $frequest->request = 'REQ'.random_int(1,10000);
                $frequest->save();

                if ($frequest->save())
                {
                    $users = User::all();

                    foreach ($users as $user) {

                        if ($user->hasRole('admin'))
                        {
                            try {
                                $data = [
                                    'greeting' => 'Good day, '.$user->full_name,
                                    'subject' => $frequest->user->full_name.' has submitted a humber request. ',
                                    'body' => $frequest->user->full_name.' has requested a food humber for '.$frequest->allocation,
                                    'action' => 'Approve Request',
                                    'actionUrl' => 'http://127.0.0.1:8000/frequests',
                                ];

                                $user->notify(new FoodRequestNotification($data));

                                return redirect('home')->with('success','Your request has been submitted successfully.');

                            } catch (\Exception $e) {

                                return redirect('home')->with('success','Your request has been submitted successfully. Please be advised that the sytem failed to send email notification.');
                            }
                        }
                    }
                }

            } catch (\Exception $e) {
                echo 'Error'.$e;
            }
        }
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
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUsername($paynumber) {
        $name = DB::table("users")
          ->where("paynumber",$paynumber)
          ->pluck("name");

        return response()->json($name);
    }

    public function approveRequest($id)
    {
        $request = FoodRequest::findOrFail($id);

        // check for request type
        if ($request->type == 'extra')
        {
            dd("extra humbers not coded yet");
        } else {

            $allocation = Allocation::where('allocation',$request->allocation)->first();
            $settings = HumberSetting::where('id',1)->first();

            if ($allocation)
            {
                $request_type = $request->type;

                if ($request_type == 'food' )
                {
                    if ($settings->food_available == 1)
                    {
                        if ($allocation->food_allocation == 1)
                        {
                            // check if there is a request approved for the same allocation
                            $previous = FoodRequest::where('allocation',$request->allocation)
                                                    ->where('type','=','food')
                                                    ->where('status','=','approved')
                                                    ->where('trash','=',1)
                                                    ->first();

                            if (!$previous)
                            {
                                // check if there is a jobcard with non allocated units
                                $jobcard = Jobcard::where('remaining','>',0)->first();

                                if ($jobcard)
                                {
                                    $job_month = $request->paynumber.$jobcard->card_month;
                                    $user_status_activated = $allocation->user->activated;
                                    if ($user_status_activated == 1)
                                    {
                                        $request->status = "approved";
                                        $request->trash = 1;
                                        $request->done_by = Auth::user()->name;
                                        $request->updated_at = now();
                                        $request->jobcard = $jobcard->card_number;
                                        $request->save();

                                        $jobcard->updated_at = now();

                                        if ($job_month == $request->allocation)
                                        {
                                            $jobcard->issued += 1;

                                        } else {

                                            $jobcard->extras_previous += 1;
                                        }
                                        $jobcard->remaining -= 1;
                                        $jobcard->save();

                                        return redirect('frequests')->with('success','Request has been approved successfully');

                                    } else {

                                        return back()->with('error',"Selected User has been De Activated. Please contact admin for user to be activated.");
                                    }
                                    return redirect('frequests')->with('success','Humber request has been approved successfully');

                                } else {

                                    return back()->with('error','There is no jobcard for approving the request. Please contact Admin for more info');
                                }

                            } else {

                                if ($request->id == $previous->id)
                                {
                                    return back()->with('warning','This request has been approved already. ');
                                } else {

                                    $request->status = "rejected";
                                    $request->delete();
                                    $request->save();

                                    return back()->with('warning','Requested humber has been approved. Please check on your approved requests.');
                                }
                            }

                        }
                        else {
                            return redirect()->back()->with('error','Food humber has been collected.');
                        }
                    } else {

                        return back()->with('error','Food Humbers are currently unavailable');
                    }

                } else {

                    dd("meat humber module is still in progress !!");
                }
            } else {

                return back()->with('info','User has no allocation.');
            }
        }

    }

    public function rejectRequest($id)
    {
        $frequest = FoodRequest::findOrFail($id);
        return view('frequests.reject',compact('frequest'));
    }

    public function getApproved()
    {
        $requests = FoodRequest::where('status','=','approved')->get();
        return view('frequests.approved',compact('requests'));
    }

    public function getPending()
    {
        $frequests = FoodRequest::where('status','=','not approved')->get();
        return view('frequests.pending',compact('frequests'));
    }

    public function getAllocation($paynumber) {

        $allocation = Allocation::where('paynumber',$paynumber)
                    ->where('food_allocation','>',0)
                    ->where('status','not collected')
                    ->pluck('allocation');

        return response()->json($allocation);
    }
}
