<?php

namespace App\Exports;

use App\Models\Indicator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IndicatorsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Indicator::with('responses')->get(); // Assuming 'responses' is the relationship name
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Status', 'Progress', 'Created At', 'Updated At', 'Response ID', 'Response State', 'Response Notes']; // Add other relevant response fields
    }
}
