<?php

namespace App\Http\Controllers\Api\V1\Farmer;


use App\CentralLogics\Helpers;
use App\Enums\SeedlingServiceStatuses;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\SeedlingService;
use Illuminate\Http\Request;

class SeedlingsController extends Controller
{

    function __construct()
    {

    }

    public function seedlings(Request $request)
    {
        $paginator = SeedlingService::with(['seedType', 'nursery','images'])->where('share_with_farmers', true) ->whereIn('status', [
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


}
