<?php

namespace App\Imports;

use App\Events\Import;
use App\Exports\StaffsReqisterTemplateExport;
use App\Helpers\DateHelper;
use App\Models\Gender;
use App\Models\Marital;
use App\Models\Staff;
use App\Models\StaffDesignations;
use App\Models\StaffStatus;
use App\Models\Students;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Imports\HeadingRowFormatter;


HeadingRowFormatter::
    default('none');

class StaffsImport implements
    ToCollection,
    WithHeadingRow,
    SkipsOnError,
    WithEvents,
    WithChunkReading,
    ShouldQueue
{
    use Importable, SkipsErrors, RegistersEventListeners;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    private $row = 0;

    public function collection(Collection $rows)
    {
        event(new Import([
            'success'   => true,
            'data' => [],
            'total' => count($rows),

        ], request('console', '#console')));
        $_template = new StaffsReqisterTemplateExport;
        foreach ($rows->toArray() as $row) {
            ++$this->row;

            $row = array_values($row);
            if ($row && count($row) >= count($_template->headings())) {

                $fullname_km = explode(' ', $row[3]);
                $fullname_en = explode(' ', $row[4]);

                if (StaffDesignations::where('km', $row[1])->first() && StaffStatus::where('km', $row[2])->first() && Gender::where('km', $row[5])->first() && Marital::where('km', $row[7])->first()) {
                    request()->merge([
                        'first_name_km' => $fullname_km[0],
                        'last_name_km' => $fullname_km[1],
                        'first_name_en' => $fullname_en[0],
                        'last_name_en' => $fullname_en[1],
                        'gender' => Gender::where('km', $row[5])->first()->id,
                        'date_of_birth' => DateHelper::convert($row[6]),
                        'marital'    => Marital::where('km', $row[7])->first()->id,
                        'permanent_address' =>  $row[8],
                        'temporaray_address' =>  $row[9],
                        'phone' =>  $row[10],
                        'email' =>  $row[11],
                        'nationality'   => 1,
                        'mother_tong'   => 1,
                        //
                        'institute' => 1,
                        'designation' => StaffDesignations::where('km', $row[1])->first()->id,
                        'status' => StaffStatus::where('km', $row[2])->first()->id,
                        //
                        'father_fullname'     => $row[12],
                        'father_occupation'   => $row[13],
                        'father_phone'        => $row[14],

                        'mother_fullname'     => $row[15],
                        'mother_occupation'   => $row[16],
                        'mother_phone'        => $row[17],
                    ]);

                    $add = Staff::register();
                    $add['data'] = $row;
                    $add['row'] = $this->row;
                    event(new Import($add, request('console', '#console')));
                } else {

                    if (!StaffDesignations::where('km', $row[1])->first()) {
                        $errors[] = 'ផ្នែក & តួនាទី : ' . $row[1] . 'តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។';
                    }
                    if (!StaffStatus::where('km', $row[2])->first()) {
                        $errors[] = 'ស្ថានភាព : ' . $row[2] . 'តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។';
                    }
                    if (!Gender::where('km', $row[5])->first()) {
                        $errors[] = 'ភេទ : ' . $row[5] . 'តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។';
                    }

                    if (!Marital::where('km', $row[7])->first()) {
                        $errors[] = 'ស្ថានភាពគ្រួសារ : ' . $row[7] . 'តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។';
                    }

                    event(new Import([
                        'success'   => false,
                        'errors'   => $errors,
                        'data' => $row,
                        'row' => $this->row,

                    ], request('console', '#console')));
                }
            } else {
                event(new Import([
                    'success'   => false,
                    'errors'   => [
                        'ទម្រងដែលអ្នកបញ្ចូលនេះ មិនត្រូវគ្នាជាមួយគំរូខាងលើទេ។'
                    ],
                    'data' => $row,
                    'row' => $this->row,

                ], request('console', '#console')));
            }
        }
    }
    public function headingRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
