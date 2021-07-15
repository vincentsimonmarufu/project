<?php

namespace App\Http\Controllers;

use App\Models\Jobcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobcardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobcards = Jobcard::all();
        return view('jobcards.index',compact('jobcards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('jobcards.create');
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
            'card_number' => 'required|unique:jobcards',
            'date_opened' => 'required',
            'card_month' => 'required',
            'card_type' => 'required',
            'quantity' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else {
            try {
                $food = Jobcard::where('card_type','=','food')->get();

                if($food->count() == 0)
                {
                    $card = Jobcard::create([
                        'card_number' => $request->input('card_number'),
                        'date_opened' => $request->input('date_opened'),
                        'card_month' => $request->input('card_month'),
                        'card_type' => $request->input('card_type'),
                        'quantity' => $request->input('quantity'),
                        'remaining' => $request->input('quantity'),
                    ]);
                    $card->save();

                    return redirect('jobcards')->with('success','Jobcard has been created successfully');

                } else {
                    $i = 0;
                    foreach ($food as $f)
                    {
                        if ($f->remaining > 0)
                        {
                            $i++;
                        }
                    }

                    if ($i > 0)
                    {
                        return back()->with('error','There is a jobcard with remaining units');
                    } else {
                        $card = Jobcard::create([
                            'card_number' => $request->input('card_number'),
                            'date_opened' => $request->input('date_opened'),
                            'card_month' => $request->input('card_month'),
                            'card_type' => $request->input('card_type'),
                            'quantity' => $request->input('quantity'),
                            'remaining' => $request->input('quantity'),
                        ]);
                        $card->save();

                        return redirect('jobcards')->with('success','Job card has been added successfully');
                    }
                }

            } catch (\Exception $e) {
                echo "Error - ".$e;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jobcard  $jobcard
     * @return \Illuminate\Http\Response
     */
    public function show(Jobcard $jobcard)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Jobcard  $jobcard
     * @return \Illuminate\Http\Response
     */
    public function edit(Jobcard $jobcard)
    {
        return view('jobcards.edit',compact('jobcard'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jobcard  $jobcard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jobcard $jobcard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jobcard  $jobcard
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobcard = Jobcard::findOrFail($id);
        $jobcard->delete();

        return redirect('jobcards')->with('success','Jobcard has been deleted successfully');
    }

    public function importJobcards()
    {
        return view('jobcards.import');
    }
}
