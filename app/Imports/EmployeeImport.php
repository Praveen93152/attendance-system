<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class EmployeeImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $errors = [];

        foreach ($rows as $index => $row) {
            // Collect all branch IDs from the row
            Log::info('Processing row: ', $row->toArray());

            $branchIds = [
                $row['mon_branch_id'],
                $row['tue_branch_id'],
                $row['wed_branch_id'],
                $row['thur_branch_id'],
                $row['fri_branch_id'],
                $row['sat_branch_id'],
            ];

            // Remove duplicates from branchIds
            $branchIds = array_unique($branchIds);

            // Validate each branch_id exists in branches table
            foreach ($branchIds as $branchId) {
                if (!Branch::find($branchId)) {
                    $errors[] = "Row " . ($index + 2) . ": Branch ID {$branchId} does not exist in branches table.";
                }
            }

            if (empty($errors)) {
                try {
                    User::updateOrCreate(
                        ['employee_code' => $row['employee_code']],
                        [
                            'employee_name' => $row['name'],
                            'mobile_no' => $row['mobile_number'],
                            'branch_ids' => json_encode($branchIds),
                            'role'=>'rc',
                        ]
                    );
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    public function rules(): array
    {
        return [
            '*.employee_code' => 'required|unique:users,employee_code',
            '*.mobile_number' => 'required|unique:users,mobile_no',
            '*.mon_branch_id' => 'required|exists:branches,id',
            '*.tue_branch_id' => 'required|exists:branches,id',
            '*.wed_branch_id' => 'required|exists:branches,id',
            '*.thur_branch_id' => 'required|exists:branches,id',
            '*.fri_branch_id' => 'required|exists:branches,id',
            '*.sat_branch_id' => 'required|exists:branches,id',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.employee_code.required' => 'The employee_code field is required.',
            '*.employee_code.unique' => 'The employee_code has already been taken.',
            '*.mobile_number.required' => 'The mobile_number field is required.',
            '*.mobile_number.unique' => 'The mobile_number has already been taken.',
            '*.mon_branch_id.required' => 'The mon_branch_id field is required.',
            '*.mon_branch_id.exists' => 'The mon_branch_id does not exist in branches table.',
            '*.tue_branch_id.required' => 'The tue_branch_id field is required.',
            '*.tue_branch_id.exists' => 'The tue_branch_id does not exist in branches table.',
            '*.wed_branch_id.required' => 'The wed_branch_id field is required.',
            '*.wed_branch_id.exists' => 'The wed_branch_id does not exist in branches table.',
            '*.thur_branch_id.required' => 'The thur_branch_id field is required.',
            '*.thur_branch_id.exists' => 'The thur_branch_id does not exist in branches table.',
            '*.fri_branch_id.required' => 'The fri_branch_id field is required.',
            '*.fri_branch_id.exists' => 'The fri_branch_id does not exist in branches table.',
            '*.sat_branch_id.required' => 'The sat_branch_id field is required.',
            '*.sat_branch_id.exists' => 'The sat_branch_id does not exist in branches table.',
        ];
    }
}
