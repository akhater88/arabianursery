<?php

namespace App\Http\Controllers;

use App\Models\FarmUser;
use Illuminate\Http\Request;


class NurseryController extends Controller
{
    public function showNurseryFarmers(Request $request){
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }

        $farmers = $user->nursery->farmUsers();

        if($request->farm_user_name != null){
            $farmers = $farmers->where('name','like','%' . trim($request->farm_user_name) . '%');
        }

        if($request->farm_user_phone_number != null){
            $farmers = $farmers->where('mobile_number','like','%' . trim($request->farm_user_phone_number) . '%');
        }

        $farmersFiltered = $farmers->withTrashed()
                                    ->get()
                                    ->keyBy('id');
        $seedlingServices = $user->nursery->seedlingServices;
        $seedlingPurchase = $user->nursery->seedlingPurchaseRequests->where('requestedby_type', FarmUser::class);
        $totalTrays = $seedlingServices->sum('tray_count');
        $farmSeedlings = $seedlingServices->groupBy('farm_user_id');

        $farmSeedlingPurchases = $seedlingPurchase->groupBy('farm_user_id');

        $sumSeedlingPurchaseTrayByFarmer = collect();
        foreach ($farmSeedlingPurchases as $key => $farmSeedlingPurchase){
            $sumSeedlingPurchaseTrayByFarmer->put( $key,$farmSeedlingPurchase->sum('tray_count'));
        }

        $sumSeedlingTrayByFarmer = collect();
        foreach ($farmSeedlings as $key => $farmSeedling){
            $sumSeedlingTrayByFarmer->put( $key,$farmSeedling->sum('tray_count'));
        }

        $instalmentsPaid = $user->nursery->installments->where('invoice_number','<>',null)->where('farm_user_id_type','FarmUser')->where('type', 'Collection')->groupBy('farm_user_id');

        $sumInstalmentsPaidByFarmer = collect();
        foreach ($instalmentsPaid as $instalmentPaid){
            $key = 0;
            $sumInstalmentsPaidByFarmer->put( $instalmentPaid[$key]->farm_user_id,$instalmentPaid->sum('amount'));
            $key++;
        }

        $instalmentsNotPaid = $user->nursery->installments->whereNull('invoice_number')->where('farm_user_id_type','FarmUser')->where('type', 'Collection')->groupBy('farm_user_id');
        $sumInstalmentsNotPaidByFarmer = collect();
        foreach ($instalmentsNotPaid as $instalmentNotPaid){
            $key = 0;
            $sumInstalmentsNotPaidByFarmer->put( $instalmentNotPaid[$key]->farm_user_id,$instalmentNotPaid->sum('amount'));
            $key++;
        }

        return view('nursery-farmers.index', [
            'farmers' => $farmersFiltered,
            'instalmentsPaid' => $instalmentsPaid,
            'instalmentsNotPaid' => $instalmentsNotPaid,
            'sumPaidInstalments' => $sumInstalmentsPaidByFarmer->toArray(),
            'sumNotPaidInstalments' => $sumInstalmentsNotPaidByFarmer->toArray(),
            'sumSeedlingTrayByFarmer' => $sumSeedlingTrayByFarmer->toArray(),
            'sumSeedlingPurchaseTrayByFarmer' => $sumSeedlingPurchaseTrayByFarmer->toArray(),
            'totalTrays' => $totalTrays,
            'page_title' => 'عملاء المشتل'
        ]);

    }



}
