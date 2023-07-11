<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RentalExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $rentals;

    public function __construct($rentals)
    {
        $this->rentals = $rentals;
    }

    public function collection()
    {
        return $this->extractData();
    }

    public function headings(): array
    {
        return [
            'User name',
            'Type',
            'Model',
            'Year',
            'Price per day',
            'Start date',
            'End date'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E0E0E0',
                ],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }

    private function extractData(): Collection
    {
        $data = [];

        foreach ($this->rentals as $rental) {
            $data[] = [
                'User name' => $rental->user->name,
                'Type' => $rental->car->type,
                'Model' => $rental->car->model,
                'Year' => $rental->car->year,
                'Price per day' => $rental->car->price_per_day,
                'Start date' => $rental->start_date,
                'End date' => $rental->end_date,
            ];
        }

        return new Collection($data);
    }
}
