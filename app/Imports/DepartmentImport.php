<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToCollection ,WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $current = Department::where('name',$row['name'])->first();

            if (!$current)
            {
                $department = Department::create([
                    'name' => $row['name'],
                    'manager' => $row['manager'],
                    'assistant' => $row['assistant'],
                ]);
                $department->save();

            }
        }
    }
}
