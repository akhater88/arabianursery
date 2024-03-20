<?php

namespace App\Http\Controllers\Api\V1\Farmer;

use App\CentralLogics\Helpers;
use App\Models\FarmUser;
use App\Models\Nursery;
use App\Models\SeedlingPurchaseRequest;
use App\Models\SeedlingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Enums\SeedlingServiceStatuses;


class FarmController extends Controller
{
    public function getFarmerProfile(Request $request){
        if(Auth::check()){
            $user = Auth::user();
            $farm = $user->farm;
            $userArray = $user->toArray();
            $userArray['farm'] = $farm->toArray();
            return response()->json($userArray, 200);
        }
        return response()->json([], 401);
    }

    public function getFarmerNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications; // Retrieve all notifications
        $user->unreadNotifications->markAsRead();
        return response()->json($notifications);
    }

    public function getSeedling(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user = Auth::user();
        $paginator = SeedlingService::with(['seedType', 'nursery','images'])
            ->where(['farm_user_id' => $user->id])
            ->whereIn('status', [
                SeedlingServiceStatuses::SEEDS_NOT_RECEIVED,
                SeedlingServiceStatuses::SEEDS_RECEIVED,
                SeedlingServiceStatuses::GERMINATION_COMPLETED,
                SeedlingServiceStatuses::READY_FOR_PICKUP,
                SeedlingServiceStatuses::DELIVERED
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $seedlings = Helpers::seedling_data_formatting($paginator->items(), true);
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'seedlings' => $seedlings
        ];
        return response()->json($data, 200);
    }

    public function getSeedlingById($seedlingID){
        $user = Auth::user();
        $seedlingService = SeedlingService::with(['seedType', 'nursery', 'images' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->withSum([
            'seedlingPurchaseRequests' => function ($qry){ $qry->where('status',1);}
        ], 'tray_count')->where('id', $seedlingID)->where('farm_user_id', $user->id)->first();

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

    public function reserveSeedlings(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'seedling_id' => 'required',
            'number_of_trays' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $requestedBy = $user->id;
        $requestedByType = FarmUser::class;

        $seedling = SeedlingService::findOrFail($request->seedling_id);
        SeedlingPurchaseRequest::create([
            "nursery_id" => $seedling->nursery_id,
            "nursery_user_id" => $seedling->nursery_user_id,
            "farm_user_id" => $requestedBy,
            "seedling_service_id" => $seedling->id,
            "tray_count" => $request->number_of_trays,
            "price_per_tray" => $seedling->tray_shared_price?? '10' ,
            'requestedby' => $requestedBy,
            'requestedby_type' => $requestedByType,
            'status' => 2,
            "cash" => ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount],
        ]);
        return response()->json([],200);
    }

    public function getReserveSeedlings(Request $request){
        $user = $request->user();
        $requestedByType = FarmUser::class;
        $seedlingPurchaseRequestsIDs = SeedlingPurchaseRequest::where('requestedby',$user->id)->where('requestedby_type',$requestedByType)->pluck('seedling_service_id')->toArray();

        $reservedSeedlings = SeedlingService::with([
            'nursery',
            'seedType',
            'seedlingPurchaseRequests' => function ($qry) use ($user, $requestedByType) {
                $qry->where('requestedby', $user->id);
                $qry->where('requestedby_type', $requestedByType);
            },
            'images'
            ])
            ->withSum([
                'seedlingPurchaseRequests' => function ($qry){ $qry->where('status',1);}
            ], 'tray_count')->whereIn('id', $seedlingPurchaseRequestsIDs)->orderBy('created_at', 'desc')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $seedlings = Helpers::reserved_seedling_data_formatting($reservedSeedlings->items(), true);
        $data = [
            'total_size' => $reservedSeedlings->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'seedlings' => $seedlings
        ];
        return response()->json($data, 200);
    }
}
