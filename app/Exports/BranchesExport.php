<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BranchesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Branch::select('id', 'client_name', 'state', 'branch')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Client Name',
            'State',
            'Branch',
        ];
    }
}
