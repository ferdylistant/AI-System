<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PenulisExport implements FromView, WithStyles, ShouldAutoSize, WithColumnFormatting, WithEvents
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ]
            ],
        ];
    }
    public function columnFormats(): array
    {
        return [
            'V' => NumberFormat::FORMAT_NUMBER,
            'W' => NumberFormat::FORMAT_NUMBER,
            'Y' => NumberFormat::FORMAT_NUMBER,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                /** @var Worksheet $sheet */
                foreach ($event->sheet->getColumnIterator('P') as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        if ($cell->getValue() != "" && str_contains($cell->getValue(), '://')) {
                            $cell->setHyperlink(new Hyperlink($cell->getValue(), 'Read'));

                            // Upd: Link styling added
                            $event->sheet->getStyle($cell->getCoordinate())->applyFromArray([
                                'font' => [
                                    'color' => ['rgb' => '0000FF'],
                                    'underline' => 'single'
                                ]
                            ]);
                        }
                    }
                }
            },
        ];
    }
    public function view(): View
    {
        $data = DB::table('penerbitan_penulis')->get();
        return view('penerbitan.penulis.export-excel', [
            'data' => $data
        ]);
    }
}
