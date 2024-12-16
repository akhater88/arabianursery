<?php

namespace App\Http\Controllers\Api\V1\Farmer;


use App\CentralLogics\Helpers;
use App\Enums\SeedlingServiceStatuses;
use App\Http\Controllers\Controller;
use App\Models\Nursery;
use App\Models\Post;
use App\Models\SeedlingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class nurseriesController extends Controller
{

    function __construct()
    {

    }

    public function nurseries(Request $request)
    {
        $paginator = Nursery::paginate($request['limit'], ['*'], 'page', $request['offset']);;

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'nurseries' => $paginator->items()
        ];
        return response()->json($data, 200);
    }

    public function nurseriesById($id)
    {
        $userId = 0;
        if(Auth::check()) {
            $userId = Auth::user()->id;
        }

            $data = Nursery::with(['seedlingServices' => function($qry) use ($userId) {
                    $qry ->where(['farm_user_id' =>$userId])
                    ->whereIn('status', [
                        SeedlingServiceStatuses::SEEDS_NOT_RECEIVED,
                        SeedlingServiceStatuses::SEEDS_RECEIVED,
                        SeedlingServiceStatuses::GERMINATION_COMPLETED,
                        SeedlingServiceStatuses::READY_FOR_PICKUP,
                        SeedlingServiceStatuses::DELIVERED
                    ]);
                },'nurserySeedsSales'])->find($id);

            return response()->json($data, 200);

    }

    public function getSeedlingById($seedlingID){
        $seedlingService = SeedlingService::with(['seedType', 'nursery', 'images' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->withSum([
            'seedlingPurchaseRequests' => function ($qry){ $qry->where('status',1);}
        ], 'tray_count')->where('id', $seedlingID)->where('share_with_farmers', true)->first();

        if($seedlingService){
            $data = $seedlingService->toArray();
            $seedlingAge = $seedlingService->created_at->diffInDays(\Carbon\Carbon::now());
            $handedPeriod = $data['germination_period'] - $seedlingAge;
            $handedDate = \Carbon\Carbon::now()->addDays($handedPeriod)->format('d-m-Y');
            $data['expected_handed_date'] = $handedDate;
            $data['expected_handed_period'] = $handedPeriod;
            $data['available_tray'] = $data['tray_count'] - $data['seedling_purchase_requests_sum_tray_count'];
            $data['show_price'] = $data['tray_shared_price'] != null ? true : false;
            return response()->json($data, 200);
        }
        else{
            return response()->json(['message' => 'Not Found'], 404);
        }

    }


}
