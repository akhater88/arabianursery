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
        $paginator = Nursery::with(['nurseryUsers'])->paginate($request['limit'], ['*'], 'page', $request['offset']);


        $nurseries = collect($paginator->items())->map(function ($nursery) {
            $owner = $nursery->nurseryUsers->first(); // Get the first user (if exists)
            return [
                'id' => $nursery->id,
                'name' => $nursery->name,
                'location' => $nursery->location,
                'address' => $nursery->address,
                'created_at' => $nursery->created_at,
                'updated_at' => $nursery->updated_at,
                'image_path' => $nursery->image_path,
                'description' => $nursery->description,
                'owner' => $owner ? [
                    'name' => $owner->name,
                    'mobile_number' => $owner->country_code . $owner->mobile_number,
                    'email' => $owner->email,
                ] :  [
                    'name' => '',
                    'mobile_number' => '',
                    'email' => '',
                ], // Handle case where there's no owner
            ];
        });

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'nurseries' => $nurseries,
        ];

        return response()->json($data, 200);
    }

    public function nurseriesById($id)
    {

        $data = Nursery::with(['nurseryUsers' => function ($query) {
            $query->first(); // Include 'id' as foreign key
        }])->find($id);
        $data['owner'] = [
            'name'=> $data->nurseryUsers[0]['name'],
            'mobile_number' => $data->nurseryUsers[0]['country_code'].$data->nurseryUsers[0]['mobile_number'],
            'email' => $data->nurseryUsers[0]['email']
        ];

        unset($data->nurseryUsers);
        return response()->json($data, 200);

    }

    public function getSeedlingById(Request $request, $id){
        $userId = 0;
        if(Auth::check()) {
            $userId = Auth::user()->id;
        }
        $paginator = SeedlingService::with(['seedType', 'nursery','images'])->whereIn('status', [
            SeedlingServiceStatuses::SEEDS_NOT_RECEIVED,
            SeedlingServiceStatuses::SEEDS_RECEIVED,
            SeedlingServiceStatuses::GERMINATION_COMPLETED,
            SeedlingServiceStatuses::READY_FOR_PICKUP,
            SeedlingServiceStatuses::DELIVERED
        ])
            ->where('farm_user_id', $userId)
            ->where('nursery_id', $id)
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

    public function getNurserySeedlingForSaleById(Request $request,$id){

        $paginator = SeedlingService::with(['seedType', 'nursery','images'])->withSum([
            'seedlingPurchaseRequests' => function ($qry){ $qry->where('status',1);}
        ], 'tray_count')->where('share_with_farmers', true) ->whereIn('status', [
            SeedlingServiceStatuses::SEEDS_NOT_RECEIVED,
            SeedlingServiceStatuses::SEEDS_RECEIVED,
            SeedlingServiceStatuses::GERMINATION_COMPLETED,
            SeedlingServiceStatuses::READY_FOR_PICKUP,
            SeedlingServiceStatuses::DELIVERED
        ])
            ->where('nursery_id', $id)
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
