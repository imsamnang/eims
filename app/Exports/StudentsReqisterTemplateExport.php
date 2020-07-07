<?php

namespace App\Exports;

use App\Models\Gender;
use App\Models\Marital;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class StudentsReqisterTemplateExport implements FromCollection, ShouldAutoSize, WithHeadings, WithEvents
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {


        $stuents[] =  [
            'id'    => 1,
            'fullname_km' => 'សែម គឹមសាន',
            'fullname_en' => 'Sem Keamsan',
            'gender'      => 'ប្រុស',
            'dob'         => '16/10/1998',
            'marital'     => 'លីវ',
            'permanent_address'     => 'ត្រពាំងទឹម កណ្តែក ប្រាសាទបាគង ខេត្តសៀមរាប',
            'temporaray_address'     => 'ត្រពាំងទឹម កណ្តែក ប្រាសាទបាគង ខេត្តសៀមរាប',
            'phone'     => '0969140554',
            'email'     => 'keamsan.sem@gmail.com',
        ];

        return collect($stuents);
    }
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Photo');
        $drawing->setDescription('My Photo');
        $drawing->setPath(public_path('/assets/img/user/male.jpg'));
        $drawing->setHeight(70);
        $drawing->setCoordinates('J2');
        return $drawing;
    }

    /**
     * @return array
     */
    public function headings(): array
    {

        $heading = [
            'ល.រ',
            'ឈ្មោះពេញ (ជាភាសាខ្មែរ)',
            'ឈ្មោះពេញ (ជាភាសាឡាតាំង)',
            'ភេទ',
            'ថ្ងៃខែឆ្នាំកំណើត',
            'ស្ថានភាពគ្រួសារ',
            'អាស័យ​ដ្ឋាន​អ​ចិ​ន្រ្តៃ​យ៍',
            'អាស័យដ្ឋានបណ្តោះអាសន្ន',
            'លេខទូរស័ព្ទ',
            'អ៊ីម៉ែល',
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
                $event->sheet->getDelegate()->getStyle('A1:J1')
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
                                    'rgb' => str_replace('#', '', config('app.theme_color.color'))
                                ]
                            ],

                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]
                    );
                // get layout counts (add 1 to rows for heading row)
                $row_count = 51;
                $column_count = 10;

                for ($i = 2; $i <= $row_count; $i++) {
                    $event->sheet->getDelegate()->getStyle('A' . $i . ':J' . $i)->getFont()
                        ->setName('Khmer OS Battambang')
                        ->setSize(11);
                    $event->sheet->getDelegate()->getStyle('A' . $i . ':J' . $i)->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }

                // set dropdown column
                $drop_column_gender = 'D';
                $drop_column_marital = 'F';

                // set dropdown options
                $optionsGender = Gender::pluck('km')->toArray();
                $optionsMarital = Marital::pluck('km')->toArray();


                // set dropdown list for first data row
                $validation = $event->sheet->getCell("{$drop_column_gender}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('ភេទ');
                $validation->setError('តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។');
                $validation->setFormula1(sprintf('"%s"', implode(',', $optionsGender)));
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_gender}{$i}")->setDataValidation(clone $validation);
                }

                // set dropdown list for first data row
                $validation = $event->sheet->getCell("{$drop_column_marital}2")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('ភេទ');
                $validation->setError('តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។');
                $validation->setFormula1(sprintf('"%s"', implode(',', $optionsMarital)));
                for ($i = 3; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column_marital}{$i}")->setDataValidation(clone $validation);
                }

                // set columns to autosize
                for ($i = 1; $i <= $column_count; $i++) {
                    $column = Coordinate::stringFromColumnIndex($i);
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
