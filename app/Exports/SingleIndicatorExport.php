<?php

namespace App\Exports;

use App\Models\Indicator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SingleIndicatorExport implements FromCollection, WithHeadings
{
    protected $indicatorId;

    public function __construct($indicatorId)
    {
        $this->indicatorId = $indicatorId;
    }

    public function collection()
    {
        return Indicator::with('responses')->where('id', $this->indicatorId)->get();
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Status', 'Progress', 'Created At', 'Updated At', 'Response ID', 'Response State', 'Response Notes']; // Add other relevant response fields
    }
}
