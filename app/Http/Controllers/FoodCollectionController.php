<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use App\Models\FoodCollection;
use App\Models\FoodRequest;
use App\Models\Jobcard;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FoodCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = FoodCollection::latest()->get();
        return view('food_collections.index',compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $requests = FoodRequest::where('trash','=',1)
                                ->where('status','=','approved')
                                ->where('issued_on','=',null)
                                ->get();
        $jobcards = Jobcard::where('remaining','>',0)->where('card_type','=','food')->get();
        return view('food_collections.create',compact('requests','jobcards'));
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
            'jobcard' => 'required',
            'frequest' => 'required|unique:food_collections,frequest',
            'allocation' => 'required|unique:food_collections,allocation',
            'issue_date' => 'required',
            'iscollector' => 'required',
            'pin' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();

        } else {
            try {
                // check the pin
                $entered = $request->pin;
                $user = User::where('paynumber','=',$request->paynumber)->first();
                $request_detail = FoodRequest::where('allocation',$request->allocation)->first();

                if (Hash::check($entered, $user->pin))
                {
                    // check if the jobcard jas remaining units
                    $jobcard = Jobcard::where('card_number','=',$request->jobcard)->first();

                    if ($jobcard->remaining >= 1)
                    {
                        $collection = new FoodCollection();
                        $collection->paynumber = $request->paynumber;
                        $collection->jobcard = $jobcard->card_number;
                        $collection->issue_date = $request->issue_date;
                        $collection->allocation = $request->allocation;
                        $collection->frequest = $request->frequest;
                        $collection->done_by = Auth::user()->full_name;
                        $collection->status = 1;

                        if ($request->iscollector == 'self')
                        {
                            $collection->self = 1;

                        } else {
                            // check if id number is correct
                            $collection->collected_by = $request->collected_by;
                            $benef = Beneficiary::where('first_name',$request->collected_by)->first();

                            if ($benef->id_number == $request->id_number)
                            {
                                $collection->id_number = $request->id_number;
                                $collection->self = 0;

                            } else {

                                return back()->with('error','Entered ID Number does not match our records');
                            }

                        }

                        $collection->save();

                        if ($collection->save())
                        {
                            try {
                                // updating allocation
                                $user_allocat = Allocation::where('allocation',$collection->allocation)->first();
                                $user_allocat->status = 'collected';
                                $user_allocat->food_allocation -= 1;
                                $user_allocat->save();

                                // updating request
                                $request_detail->status = 'collected';
                                $request_detail->trash = 1;
                                $request_detail->updated_at = now();
                                $request_detail->issued_on = now();
                                $request_detail->save();

                                // updating user
                                $collection->user->fcount -= 1;
                                $collection->user->save();

                                $job_month = $request_detail->paynumber.$jobcard->card_month;

                                $jobcard->updated_at = now();

                                if ($job_month == $request_detail->allocation)
                                {
                                    $jobcard->issued += 1;

                                } else {

                                    $jobcard->extras_previous += 1;
                                }

                                $jobcard->remaining -= 1;
                                $jobcard->save();

                                return redirect('fcollections')->with('success','Collection has been processed successfully');

                            } catch (\Exception $e) {
                                echo "error - ".$e;
                            }

                        }

                    } else {

                        return back()->with('error','Plese contact admin to open a new jobcard');
                    }

                } else {

                    return back()->with('error','Entered Pin did not match our records');
                }

            } catch (\Exception $e) {
                echo "Error - ".$e;
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
        $collection = FoodCollection::findOrFail($id);

        return view('food_collections.show',compact('collection'));
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

    public function getFoodRequest($paynumber)
    {
        $name = DB::table("food_requests")
          ->where("paynumber",$paynumber)
          ->pluck("request");

        return response()->json($name);
    }

    public function getFoodRequestAllocation($paynumber)
    {
        $name = DB::table("food_requests")
          ->where("paynumber",$paynumber)
          ->pluck("allocation");

        return response()->json($name);
    }

    public function getUserBeneficiaries($paynumber)
    {
        $user = User::where('paynumber',$paynumber)->first()->id;

        $beneficiaries = DB::table('beneficiaries')
                            ->where('user_id','=',$user)
                            ->pluck('first_name');

        return response()->json($beneficiaries);
    }
}
