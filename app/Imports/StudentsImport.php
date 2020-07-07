<?php

namespace App\Imports;

use App\Events\Import;
use App\Helpers\DateHelper;
use App\Models\Gender;
use App\Models\Marital;
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

class StudentsImport implements
    ToCollection,
    WithHeadingRow,
    SkipsOnError,
    WithEvents,
    WithChunkReading, ShouldQueue
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

        foreach ($rows->toArray() as $row) {
            ++$this->row;
            $row = array_values($row);

            if ($row && count($row) >= 10) {

                $fullname_km = explode(' ', $row[1]);
                $fullname_en = explode(' ', $row[2]);

                if(Gender::where('km', $row[3])->first() && Marital::where('km', $row[5])->first()){
                    request()->merge([
                        'first_name_km' => $fullname_km[0],
                        'last_name_km' => $fullname_km[1],
                        'first_name_en' => $fullname_en[0],
                        'last_name_en' => $fullname_en[1],
                        'gender' => Gender::where('km', $row[3])->first()->id,
                        'date_of_birth' => DateHelper::convert($row[4]),
                        'marital'    => Marital::where('km', $row[5])->first()->id,
                        'permanent_address' =>  $row[6],
                        'temporaray_address' =>  $row[7],
                        'phone' =>  $row[8],
                        'email' =>  $row[9],
                        'nationality'   => 1,
                        'mother_tong'   => 1
                    ]);
                    $add = Students::register();
                    $add['data'] = $row;
                    $add['row'] = $this->row;
                    event(new Import($add, request('console', '#console')));
                }else{
                    if(!Gender::where('km', $row[3])->first()){
                       $errors[] = 'ភេទ : '.$row[3].'តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។';
                    }
                    if(!Marital::where('km', $row[5])->first()){
                        $errors[] = 'ស្ថានភាពគ្រួសារ : '.$row[5].'តម្លៃដែលអ្នកបានបញ្ចូលមិនមាននៅក្នុងបញ្ជីទេ។';
                    }

                    event(new Import([
                        'success'   => false,
                        'errors'   => $errors,
                        'data' => $row,
                        'row' => $this->row,

                    ], request('console', '#console')));
                }

            }else{
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
