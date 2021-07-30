<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersImport implements ToCollection , WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $adminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $permissions = config('roles.models.permission')::all();

        foreach ($rows as $row)
        {
            $current = User::where('paynumber',$row['paynumber'])->first();

            if (!$current)
            {
                // dd($row['paynumber']);
                $user = new User();

                if($row['first_name'] || $row['last_name'])
                {
                    $first = substr($row['first_name'],0,1);
                    $username = Str::lower($first.$row['last_name']);
                    $user->name = $username;
                    $user->first_name = $row['first_name'];
                    $user->last_name = $row['last_name'];
                }

                if ($row['paynumber'])
                {
                    $user->paynumber = $row['paynumber'];
                }

                if ($row['department_id'])
                {
                    $user->department_id = $row['department_id'];
                } else {
                    $user->department_id = 1;
                }

                if ($row['usertype_id'])
                {
                    $user->usertype_id = $row['usertype_id'];
                } else {
                    $user->usertype_id = 1;
                }

                $user->mobile = $row['mobile'];
                $user->activated = 1;
                $user->password = Hash::make('password');
                $user->email = $row['email'];
                $user->pin = Hash::make('1234');

                if ($row['role'])
                {
                    if ($row['role'] == "user")
                    {
                        $user->attachRole(1);
                    } else {
                        $user->attachRole(5);
                    }
                }
                $user->save();
            }
        }
    }
}
