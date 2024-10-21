<?php

namespace App\Exports;

use App\Models\Response;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResponseExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $indicatorId;

    public function __construct($indicatorId)
    {
        $this->indicatorId = $indicatorId;
    }

    public function collection()
    {
        return Response::select('responses.*', 'organisations.name as organisation_name', 'indicators.indicator_title as indicator_title', 'users.name as respondent_name') // Select the necessary columns
            ->where('indicator_id', $this->indicatorId)
            ->join('indicators', 'responses.indicator_id', '=', 'indicators.id') // Join with indicators table
            ->join('organisations', 'responses.organisation_id', '=', 'organisations.id') // Join with organisations table
            ->join('users', 'responses.user_id', '=', 'users.id') // Join with users table
            ->get();
    }


    public function headings(): array
    {
        return [
            'Response ID',
            'Indicator ID',
            'Organisation Name',
            'Respondent Name',
            'Current Status',
            'Percentage Progress From Baseline',
            'Notes',
            'Lessons',
            'Recommendations',
            'Status',
            'Response Added On',
            'Response Was Last Updated On'
        ];
    }

    public function map($response): array
    {
        return [
            $response->id,
            $response->indicator_id,
            $response->organisation_name,
            $response->respondent_name,
            $response->current,
            $response->progress,
            strip_tags($response->notes),
            strip_tags($response->lessons),
            strip_tags($response->recommendations),
            $response->status,
            $response->created_at->format('Y-m-d H:i:s'),
            $response->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,   // Response ID
            'B' => 15,   // Indicator ID
            'C' => 30,   // Organisation Name
            'D' => 25,   // Respondent Name
            'E' => 15,   // Current Status
            'F' => 30,   // Percentage Progress From Baseline
            'G' => 50,   // Notes
            'H' => 50,   // Lessons
            'I' => 50,   // Recommendations
            'J' => 20,   // Status
            'K' => 25,   // Response Added On
            'L' => 25,   // Response Was Last Updated On
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set a fixed width for columns and enable wrap text
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setWidth(-1); // Set width to 20, adjust as needed
        }

        // Wrap text for all cells and set alignment
        $sheet->getStyle('A1:L1000')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:L1000')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('A1:L1000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        return [
            1 => ['font' => ['bold' => true]], // Optionally bold the header row
        ];
    }
}
