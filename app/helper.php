<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('p')) {
    function p($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }
}

if (!function_exists('saveEmployeeImage')) {
    function saveEmployeeImage($image, $emp_id, $emp_name)
    {
        $baseDir = 'public/employee_images';

        // Get the current date components
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        // Create the directory path
        $directory = "$baseDir/$year/$month/$day";

        // Ensure the directory exists
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0755, true);
        }

        // Generate the filename
        $timestamp = date('Ymd_His');
        $fileExtension = $image->getClientOriginalExtension();
        $sanitizedEmpName = Str::slug($emp_name); // Sanitize the emp_name to avoid invalid characters
        $filename = "{$timestamp}_{$emp_id}_{$sanitizedEmpName}." . $fileExtension;

        // Save the image
        $path = $image->storeAs($directory, $filename);

        // Return the public path
        return Storage::url($path);
    }
}
