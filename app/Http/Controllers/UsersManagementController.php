<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Models\Usertype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Support\Str;

class UsersManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('usersmanagement.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $usertypes = Usertype::all();
        $roles = Role::all();
        return view('usersmanagement.create',compact('departments','usertypes','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),
        [
            'paynumber'             => 'required|max:255|unique:users|alpha_dash',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'department_id'         => 'required',
            'mobile'                => 'required',
            'activated'             => 'required',
            'usertype_id'           => 'required',
            'role'                  => 'required',
            'pin'                   => 'required',
            'password'              => 'required|min:6|max:20|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }
        else {

            try {
                $first = substr($request->first_name,0,1);
                $username = Str::lower($first.$request->last_name);

                $user = User::create([
                    'name' => $username,
                    'paynumber' => $request->input('paynumber'),
                    'first_name' => strip_tags($request->input('first_name')),
                    'last_name' => strip_tags($request->input('last_name')),
                    'mobile' => strip_tags($request->input('mobile')),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'activated' => $request->input('activated'),
                    'pin' => Hash::make($request->input('pin')),
                    'department_id' => $request->input('department_id'),
                    'usertype_id' => $request->input('usertype_id'),
                ]);

                $user->attachRole($request->input('role'));
                $user->save();

                return redirect('users')->with('success','User was created successfully');

            } catch (\Exception $th) {
                return redirect()->back()->with('error','Failed to create new user');
            }
        }

        return redirect()->back()->with('error','System was unable to create user account.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('usersmanagement.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $departments = Department::all();
        $usertypes = Usertype::all();
        $roles = Role::all();
        return view('usersmanagement.edit',compact('departments','usertypes','roles','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $emailCheck = ($request->input('email') !== '') && ($request->input('email') !== $user->email);

        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'paynumber' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required',
            'department' => 'required',
            'usertype' => 'required',
            'role' => 'required',
            'activated' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        $first = substr($request->first_name,0,1);
        $username = Str::lower($first.$request->last_name);

        $user->name = $username;
        $user->paynumber = strip_tags($request->input('paynumber'));
        $user->first_name = strip_tags($request->input('first_name'));
        $user->last_name = strip_tags($request->input('last_name'));
        $user->department_id = $request->input('department');
        $user->usertype_id = $request->input('usertype');
        $user->mobile = $request->input('mobile');

        if($emailCheck){
            $user->email = $request->input('email');
        }

        if($request->input('password') !== null){
            $user->password = Hash::make($request->input('password'));
        }

        if ($user->activated == 1)
        {
            $user->activated = $request->activated;
        }else
        {
            $user->activated = $request->activated;
        }

        $userRole = $request->input('role');
        if($userRole !== null){
            $user->detachAllRoles();
            $user->attachRole($userRole);
        }
        $user->save();

        return redirect('users')->with('success','User has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if ($user->id != $currentUser->id) {
            $user->activated = 0;
            $user->save();
            $user->delete();
            return redirect('users')->with('success','User has been deleted successfully');
        }

        return back()->with('error','You cannot delete your own account.');
    }

    public function deActivateUser($id)
    {
        $user = User::find($id);

        if($user->activated == 1)
        {
            $deactivate = DB::table('users')
                ->where('id', $id)
                ->update(['activated' => 0]);

            if ($deactivate)
            {
                return redirect('users')->with('success','User has been deactivated successfully. Please not that user will not be allocated as from date..');
            }
            else{
                return redirect()->back()->with('error','Failed to deactivate users');
            }
        }


        return redirect('users')->with('info',"The selected user is already deactivated.");

    }
}