<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

if (!function_exists('p')) {
    function p($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
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
        $filename = "{$timestamp}_{$emp_id}_{$emp_name}." . $fileExtension;

        // Save the image
        $path = $image->storeAs($directory, $filename);

        // Return the public path
        return Storage::url($path);
    }
}

