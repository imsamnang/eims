<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StaffsReportTemplateExport implements FromCollection, ShouldAutoSize, WithHeadings, WithEvents
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */


    protected $data;
    /**
     * @param array $data
     */
    function __construct($data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        $new_data = [];
        foreach ($this->data as $key => $row) {
            $new_data[] = [
                'id'    => $row['id'],
                'name'    => $row['name'],
                'dob'    => $row['date_of_birth'],
                'phone'    => $row['phone'],
                'designation'    => $row['designation'],
            ];
        }
        return collect($new_data);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $heading = [
            __('Id'),
            __('Name'),
            __('Gender'),
            __('Date of birth'),
            __('Phone'),
            __('Designation'),
            __('Other'),
        ];
        return $heading;
    }
    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:G1')
                    ->applyFromArray(
                        [
                            'font' => [
                                'name' => 'Khmer OS Battambang',
                                'size'  => 12,
                                'bold' => false,
                                'italic' => false,
                                'underline' => false,
                                'strikethrough' => false,
                                'color' => [
                                    'rgb' => 'FFFFFF'
                                ]
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'color' => [
                                    'argb' => str_replace('#', '', config('app.theme_color.color'))
                                ]
                            ],

                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]
                    );
                // get layout counts (add 1 to rows for heading row)
                $row_count = count($this->data) + 1;
                $column_count = count($this->headings());

                for ($i = 2; $i <= $row_count; $i++) {
                    $event->sheet->getDelegate()->getStyle('A' . $i . ':G' . $i)->getFont()
                        ->setName('Khmer OS Battambang')
                        ->setSize(11);
                    $event->sheet->getDelegate()->getStyle('A' . $i . ':G' . $i)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }
                // Apply array of styles to 'A1:R'.$row_count cell range
                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => str_replace('#', '', config('app.theme_color.color'))],
                        ]
                    ]
                ];
                $event->sheet->getDelegate()->getStyle('A1:G' . $row_count)->applyFromArray($styleArray);
            },
        ];
    }
}
