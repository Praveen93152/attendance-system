<?php

namespace App\Imports;

use App\Models\Branch;
use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\ValidationException;

class BranchImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * Handle the collection of rows from the Excel file.
     *
     * @param Collection $rows
     * @throws ValidationException
     */
    public function collection(Collection $rows)
    {
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // Excel rows start at 1, header is row 1

            $rowErrors = [];

            // Validate client_name exists in clients table
            if (!Client::where('clients', $row['client_name'])->exists()) {
                $rowErrors[] = "Client Name {$row['client_name']} does not exist.";
            }

            // Validate latitude and longitude are numeric
            if (!is_numeric($row['latitude'])) {
                $rowErrors[] = "Latitude must be a number.";
            }

            if (!is_numeric($row['longitude'])) {
                $rowErrors[] = "Longitude must be a number.";
            }

            // Check for required fields
            if (empty($row['client_name']) || empty($row['state']) || empty($row['branch_name']) || empty($row['latitude']) || empty($row['longitude'])) {
                $rowErrors[] = "All fields are required.";
            }

            if (
                Branch::where('client_name', $row['client_name'])
                    ->where('state', $row['state'])
                    ->where('branch', $row['branch_name'])
                    ->exists()
            ) {
                $rowErrors[] = "Branch with Client Name '{$row['client_name']}', State '{$row['state']}', and Branch '{$row['branch_name']}' already exists.";
            }

            if (!empty($rowErrors)) {
                $errors["Row {$rowNumber}"] = $rowErrors;
                continue; // Skip processing this row due to errors
            }

            // Create or update the branch
            try {
                Branch::create([
                    'client_name' => $row['client_name'],
                    'state' => $row['state'],
                    'branch' => $row['branch_name'],
                    'latitude' => $row['latitude'],
                    'longitude' => $row['longitude'],
                ]);
            } catch (\Exception $e) {
                $errors["Row {$rowNumber}"][] = $e->getMessage();
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Define validation rules for the Excel data.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.client_name' => 'required|exists:clients,clients',
            '*.state' => 'required',
            '*.branch_name' => 'required',
            '*.latitude' => 'required|numeric',
            '*.longitude' => 'required|numeric',
        ];
    }
}

