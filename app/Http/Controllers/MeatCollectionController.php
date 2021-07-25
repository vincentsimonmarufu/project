<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Beneficiary;
use App\Models\FoodRequest;
use App\Models\MeatCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MeatCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = MeatCollection::latest()->get();
        return view('mcollections.index',compact('collections'));
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
                                ->whereNull('issued_on')
                                ->where('type','=','meat')
                                ->get();
        return view('mcollections.create',compact('requests'));
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
            'frequest' => 'required|unique:meat_collections,frequest',
            'allocation' => 'required|unique:meat_collections,allocation',
            'issue_date' => 'required',
            'iscollector' => 'required',
            'pin' => 'required',
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();

        } else {

            try {

                $frequest = FoodRequest::findOrFail($request->paynumber);
                $user = User::where('paynumber',$frequest->paynumber)->first();

                $collect = new MeatCollection();
                $collect->paynumber = $user->paynumber;
                $collect->jobcard = $request->input('jobcard');
                $collect->frequest = $request->input('frequest');
                $collect->allocation = $request->input('allocation');
                $collect->issue_date = $request->input('issue_date');

                if ($request->iscollector == 'self')
                {
                    $collect->self = 1;

                } else {
                    $id_number = $request->collected_by;

                    if($id_number)
                    {
                        $beneficiary = Beneficiary::where('id_number',$id_number)->first();
                        $collect->self = 0;
                        $collect->collected_by = $beneficiary->full_name;
                        $collect->id_number = $beneficiary->id_number;

                    } else {

                        return redirect()->back()->with("error","Please select employee beneficiary");
                    }
                }

                if (Hash::check($request->pin,$user->pin))
                {
                    $collect->done_by = Auth::user()->id;
                    $collect->updated_at = now();
                    $collect->status = 1;
                    $collect->save();

                    if ($collect->save())
                    {
                        $frequest->status = "collected";
                        $frequest->issued_on = now();
                        $frequest->save();

                        $allocation = Allocation::where('allocation',$request->allocation)->first();
                        $allocation->meet_allocation -= 1;
                        $allocation->status = "collected";
                        $allocation->save();

                        $user->mcount -= 1;
                        $user->save();

                    }

                    return redirect('mcollections')->with('success','Collection has been processed successfully');
                }

            } catch (\Exception $e) {
                echo "error - ".$e;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeatCollection  $meatCollection
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $collection = MeatCollection::findOrFail($id);

        return view('mcollections.show',compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MeatCollection  $meatCollection
     * @return \Illuminate\Http\Response
     */
    public function edit(MeatCollection $meatCollection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MeatCollection  $meatCollection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MeatCollection $meatCollection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeatCollection  $meatCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(MeatCollection $meatCollection)
    {
        //
    }

    public function getRequestType($id)
    {
        $type = DB::table("food_requests")
          ->where("id",$id)
          ->pluck("type");

        return response()->json($type);
    }
}
