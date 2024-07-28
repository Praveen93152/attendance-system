<?php

namespace App\Exports;

use App\Models\DailyUploadImage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyUploadImagesExport implements FromCollection, WithHeadings
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return collect($this->results);
    }

    public function headings(): array
    {
        return [
            'Client',
            'State',
            'Branch',
            'Employee ID',
            'Employee Name',
            'Image File Name',
            'Date'
        ];
    }
}
