<?php

namespace App\Console\Commands;

use App\Models\FarmUser;
use App\Models\SeedlingService;
use App\Models\SeedType;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class seedAltaqwaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-altaqwa-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $excelFilePath = storage_path('excel/altaqwa.xlsx');


        // Read data from Excel file
//        $data = Excel::toArray([], $excelFilePath)[5];
//        foreach ($data as $index => $row ){
//            if($index == 1) continue;
//            $mobile = $row[1]?$row[1]:$index;
//            FarmUser::create([
//                'name' => $row[0],
//                'mobile_number' => $mobile,
//                'added_by_type' => 'App\Models\NurseryUser',
//                'added_by_id' => 1
//            ]);
//        }
        $data = Excel::toArray([], $excelFilePath)[0];
        foreach ($data as $index => $row ) {
            $seedlingService = [];
            if($index == 0 || $row[0] == null) {
                continue;
            } else {
                $seedlingService['farm_user_id'] = null;
                $seedlingService['type'] = 1;
                if($row[0] != 'المشتل'){
                    $farmUser = FarmUser::where('name', $row[0])->first();
                    if ($farmUser == null ){
                        $farmUser = FarmUser::create([
                            'name' => $row[0],
                            'mobile_number' => '001'.$index,
                            'added_by_type' => 'App\Models\NurseryUser',
                            'added_by_id' => 1
                        ]);
                    }
                    $seedlingService['farm_user_id'] = $farmUser->id;
                    $seedlingService['type'] = 2;
                }
                $seedlingService['nursery_id'] = 1;
                $seedlingService['nursery_user_id'] = 1;

                $seedType = SeedType::where('name',$row[1])->first();

                if($seedType == null){
                    $seedType = SeedType::create([
                        'name' => $row[1]
                    ]);
                }
                $seedlingService['seed_type_id'] = $seedType->id;
                $seedlingService['status'] = 'تم التشتيل';
                $seedlingService['installments'] = [];
                $seedlingService['greenhouse_number'] = $row[6];
                $seedlingService['tunnel_greenhouse_number'] = 1;
                $seedlingService['tray_count'] = $row[5]? $row[5] : 0;
                $seedlingService['seed_class'] = $row[2].'-'.$row[3];
                $seedlingService['seed_count'] = 0;
                $seedlingService['germination_period'] = 60;
                $seedlingService['price_per_tray'] = 2;
                $seedlingService['discount_amount'] = $row[9];
                // instalments
                for($i=13;$i<=16;$i++) {
                    if($row[$i] != null) {
                        $seedlingService['installments'][] = [
                            'amount' => $row[$i],
                            'invoice_date'=> now()->addDays(30)->format('Y-m-d'),
                            'invoice_number' => ''
                        ];
                    }
                }
                SeedlingService::create($seedlingService);
            }
        }

    }
}
