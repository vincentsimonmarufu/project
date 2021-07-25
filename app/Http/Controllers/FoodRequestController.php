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

                if ($request->type == "food")
                {
                    $frequest->type = $request->type;

                    if ($request->allocation)
                    {
                        $user_alloc = Allocation::where('allocation',$request->allocation)->first();

                        if ($user_alloc->food_allocation > 0)
                        {
                            $frequest->allocation = $request->input('allocation');
                        }
                        else {

                            $user_name = User::where('paynumber',$request->paynumber)->first();

                            return back()->with('error'," $user_name->full_name has already collected food humber for $request->allocation .");
                        }

                    } else {

                        return back()->with('error','Selected user does not have pending allocations.');
                    }

                }
                elseif ($request->type == "meat")
                {
                   $frequest->type = $request->type;

                    if ($request->allocation)
                    {
                        $user_alloc = Allocation::where('allocation',$request->allocation)->first();

                        if ($user_alloc->meet_allocation > 0)
                        {
                            $frequest->allocation = $request->input('allocation');
                        }
                        else {
                            $user_name = User::where('paynumber',$request->paynumber)->first();

                            return back()->with('error'," $user_name->full_name has already collected meat humber for $request->allocation .");
                        }

                    } else {

                        return back()->with('error','Selected user does not have pending allocations.');
                    }

                }
                else {
                    return redirect('/home')->with('warning','Extra humbers module is still in production');
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
                                    'body' => $frequest->user->full_name.' has requested a '.$request->type.' humber for '.$frequest->allocation,
                                    'action' => 'Approve Request',
                                    'actionUrl' => "http://localhost:8000/email-approve/$frequest->id/$user->paynumber",
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


    public function emailApprove($id,$approver)
    {
        $request = FoodRequest::findOrFail($id);
        $approver = User::where('paynumber',$approver)->first();

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
                                $jobcard = Jobcard::where('remaining','>',0)->where('card_type','=','food')->first();

                                if ($jobcard)
                                {
                                    $job_month = $request->paynumber.$jobcard->card_month;
                                    $user_status_activated = $allocation->user->activated;
                                    if ($user_status_activated == 1)
                                    {
                                        $request->status = "approved";
                                        $request->trash = 1;
                                        $request->done_by = Auth::user()->name;
                                        $request->approver = $approver->paynumber;
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

                    if ($settings->meat_available == 1)
                    {
                        if ($allocation->meet_allocation == 1)
                        {
                            // check if there is a request approved for the same allocation
                            $previous = FoodRequest::where('allocation',$request->allocation)
                                                    ->where('type','=','meat')
                                                    ->where('status','=','approved')
                                                    ->where('trash','=',1)
                                                    ->first();

                            if (!$previous)
                            {
                                // check if there is a jobcard with non allocated units
                                $jobcard = Jobcard::where('remaining','>',0)->where('card_type','=','meat')->first();

                                if ($jobcard)
                                {
                                    $job_month = $request->paynumber.$jobcard->card_month;
                                    $user_status_activated = $allocation->user->activated;
                                    if ($user_status_activated == 1)
                                    {
                                        $request->status = "approved";
                                        $request->trash = 1;
                                        $request->done_by = Auth::user()->name;
                                        $request->approver = $approver->paynumber;
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
                }
            } else {

                return back()->with('info','User has no allocation.');
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
        $validator = Validator::make($request->all(),[
            'paynumber' => 'required',
            'name' => 'required',
            'department' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else
        {
            try {

                $frequest = FoodRequest::findOrFail($id);

                if ($frequest->status == "collected" || $frequest->status == "approved")
                {
                    return back()->with("error"," Request has been $frequest->status already.");
                }
                else
                {
                    $frequest->reason = $request->input('reason');
                    $frequest->status = "rejected";
                    $frequest->trash = 0;
                    $frequest->save();

                    return redirect('frequests')->with('success','Request has been rejected successfully');
                }

            } catch (\Exception $e) {
                echo "error - ".$e;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $frequest = FoodRequest::findOrFail($id);

        if ( ($frequest->status == "not collected" && $frequest->issued_on == null) || ($frequest->status == "rejected" && $frequest->issued_on == null))
        {
            $frequest->delete();

            return redirect('frequests')->with('success','Request has been deleted Successfully');
        }
        else {

            if ($frequest->status == "approved")
            {
                $frequest->delete();

                return redirect('frequests')->with('success','Request has been deleted Successfully');

            } else {

                return redirect("/home")->with("warning","Request cannot be deleted.");
            }
        }
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
                                $jobcard = Jobcard::where('remaining','>',0)->where('card_type','=','food')->first();

                                if ($jobcard)
                                {
                                    $job_month = $request->paynumber.$jobcard->card_month;
                                    $user_status_activated = $allocation->user->activated;
                                    if ($user_status_activated == 1)
                                    {
                                        $request->status = "approved";
                                        $request->trash = 1;
                                        $request->done_by = Auth::user()->name;
                                        $request->approver = Auth::user()->paynumber;
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

                    if ($settings->meat_available == 1)
                    {
                        if ($allocation->meet_allocation == 1)
                        {
                            // check if there is a request approved for the same allocation
                            $previous = FoodRequest::where('allocation',$request->allocation)
                                                    ->where('type','=','meat')
                                                    ->where('status','=','approved')
                                                    ->where('trash','=',1)
                                                    ->first();

                            if (!$previous)
                            {
                                // check if there is a jobcard with non allocated units
                                $jobcard = Jobcard::where('remaining','>',0)->where('card_type','=','meat')->first();

                                if ($jobcard)
                                {
                                    $job_month = $request->paynumber.$jobcard->card_month;
                                    $user_status_activated = $allocation->user->activated;
                                    if ($user_status_activated == 1)
                                    {
                                        $request->status = "approved";
                                        $request->trash = 1;
                                        $request->done_by = Auth::user()->name;
                                        $request->approver = Auth::user()->paynumber;
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

    public function getCollectedRequests()
    {
        $frequests = FoodRequest::where('status','=','collected')->get();
        return view('frequests.collected',compact('frequests'));
    }

    public function getAllocation($paynumber) {

        // $allocation = DB::table('allocations')
        //                     ->where('paynumber','=',$paynumber)
        //                     ->where('food_allocation','>',0)
        //                     ->where('meet_allocation','>',0)
        //                     ->pluck('allocation');

        $allocation = DB::table('allocations')->where([['paynumber', '=', $paynumber],
                                                ['food_allocation', '=', 1]])
                                        ->orWhere([['meet_allocation','=',1],['paynumber','=',$paynumber]])
                                        ->pluck('allocation');
        // dd($allocation);

        return response()->json($allocation);
    }

    public function dailyApproval(){
        return view('frequests.daily');
    }

    public function dailyApprovalSearch(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'date' => 'required'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

             try {

                $approved = FoodRequest::where('status','=','approved')
                                        ->whereDate('updated_at',$request->date)
                                        ->get();

                return view('frequests.daily',compact('approved'));

             } catch (\Exception $e) {
                 echo "error - ".$e;
             }
        }
    }
}
