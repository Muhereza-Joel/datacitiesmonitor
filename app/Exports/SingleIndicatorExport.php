<?php

namespace App\Exports;

use App\Models\Indicator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SingleIndicatorExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $indicatorId;

    public function __construct($indicatorId)
    {
        $this->indicatorId = $indicatorId;
    }

    public function collection()
    {
        // Retrieve the necessary columns, including related organization details
        return Indicator::select(
            'indicators.id',
            'organisations.name as organisation_name',
            'theory_of_changes.description as toc',
            'indicators.name',
            'indicators.indicator_title',
            'indicators.definition',
            'indicators.baseline',
            'indicators.target',
            'indicators.data_source',
            'indicators.frequency',
            'indicators.responsible',
            'indicators.direction',
            'indicators.status',
            'indicators.qualitative_progress',
            'indicators.created_at',
            'indicators.updated_at',
        )
            ->where('indicators.id', $this->indicatorId)
            ->join('organisations', 'indicators.organisation_id', '=', 'organisations.id') // Join with the organizations table
            ->join('theory_of_changes', 'theory_of_change_id', '=', 'theory_of_changes.id') // Join with the organizations table
            ->get();
    }


    public function headings(): array
    {
        return [
            'Indicator ID',
            'Organisation Name',
            'Theory of Change',
            'Name',
            'Title',
            'Definition',
            'Baseline',
            'Target',
            'Data Source',
            'Frequency',
            'Responsible',
            'Progress Direction',
            'Indicator Status',
            'Qualtitative Progress',
            'Indicator Created On',
            'Indicator Last Updated On',
        ];
    }

    public function map($indicator): array
    {
        return [
            $indicator->id,
            $indicator->organisation_name,
            strip_tags($indicator->toc),
            $indicator->name,
            $indicator->indicator_title,
            $indicator->definition,
            $indicator->baseline,
            $indicator->target,
            $indicator->data_source,
            $indicator->frequency,
            $indicator->responsible,
            $indicator->direction,
            $indicator->status,
            $indicator->qualitative_progress,
            $indicator->created_at->format('Y-m-d H:i:s'),
            $indicator->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set a fixed width for columns and enable wrap text
        foreach (range('A', 'P') as $column) {
            $sheet->getColumnDimension($column)->setWidth(10); // Set width to 20, adjust as needed
        }

        // Wrap text for all cells and set alignment
        $sheet->getStyle('A1:P1000')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:P1000')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('A1:P1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        return [
            1 => ['font' => ['bold' => true]], // Optionally bold the header row
        ];
    }
}
