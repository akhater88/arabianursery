<?php

namespace App\Http\Controllers\Api\V1\Farmer;

use App\CentralLogics\Helpers;
use App\Models\SeedlingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Enums\SeedlingServiceStatuses;


class FarmController extends Controller
{
    public function getFarmerProfile(Request $request){
        $user = Auth::user();
        $farm = $user->farm;
        $userArray = $user->toArray();
        $userArray['farm'] = $farm->toArray();
        return response()->json($userArray, 200);

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
        }])->where('id', $seedlingID)->where('farm_user_id', $user->id)->first();

        if($seedlingService){
            $data = $seedlingService->toArray();
            return response()->json($data, 200);
        }
        else{
            return response()->json(['message' => 'Not Found'], 404);
        }

    }
}
