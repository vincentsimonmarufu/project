<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Allocation;

class UserController extends Controller
{
    public function myAllocations()
    {
        $user = Auth::user();
        $allocations = Allocation::where('paynumber',$user->paynumber)->get();

        return view('pages.user.my-allocations',compact('allocations'));
    }
}
