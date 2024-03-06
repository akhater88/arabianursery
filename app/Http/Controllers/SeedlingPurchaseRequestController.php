<?php

namespace App\Http\Controllers;

use App\Exports\SeedlingPurchaseRequestsExport;
use App\Http\Filters\SeedlingPurchaseRequestFilter;
use App\Http\Requests\StoreSeedlingPurchaseRequest;
use App\Http\Requests\UpdateSeedlingPurchaseRequest;
use App\Models\Farm;
use App\Models\FarmUser;
use App\Models\Nursery;
use App\Models\SeedlingPurchaseRequest;
use App\Models\SeedlingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SeedlingPurchaseRequestController extends Controller
{
    public function index(SeedlingPurchaseRequestFilter $filters)
    {
        $user = Auth::user();

        $seedlingPurchaseRequest = $user->seedlingPurchaseRequests()->with(['requestedbyUser', 'seedlingService.seedType'])
            //->where('status',1)
            ->orderBy('status', 'desc')
            ->orderBy('created_at', 'desc')
            ->filterBy($filters)
            ->paginate()
            ->withQueryString();
        //dd($seedlingPurchaseRequest);
        return view('seedling-purchase-requests.index', [
            'page_title' => 'مبيعات اشتال خاصة مشتل',
            'statuses' => SeedlingPurchaseRequest::$statuses,
            'seedling_purchase_requests' => $seedlingPurchaseRequest,
        ]);
    }

    public function show(SeedlingPurchaseRequest $seedling_purchase_request)
    {
        $user = Auth::user();
        $seedlingPurchaseRequest = $user->seedlingPurchaseRequests()->findOrFail($seedling_purchase_request->id);
        return view('seedling-purchase-requests.show', [
            'page_title' => 'تعديل طلب شراء أشتال',
            'seedling_purchase_request' => $seedlingPurchaseRequest,
        ]);
    }

    public function create()
    {
        return view('seedling-purchase-requests/create-or-edit', [
            'page_title' => 'طلب شراء أشتال',
            'seedling_purchase_request' => null,
        ]);
    }

    public function store(StoreSeedlingPurchaseRequest $request)
    {
        $requestedBy = $request->farm_user;
        $requestedByType = FarmUser::class;
        $farmUserIdType = 'FarmUser';
        if($request->requestedby_type == 'nursery'){
            $requestedBy = $request->nursery_id;
            $requestedByType = Nursery::class;
            $farmUserIdType = 'Nursery';
        }

        $seedlingPurchase = $request->user()->seedlingPurchaseRequests()->create([
            "nursery_id" => $request->user()->nursery->id,
            "farm_user_id" => $requestedBy,
            "seedling_service_id" => $request->seedling_service,
            "tray_count" => $request->tray_count,
            "price_per_tray" => $request->price_per_tray,
            'requestedby' => $requestedBy,
            'requestedby_type' => $requestedByType,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['farm_user_id'] =  $requestedBy;
                $instalmentsArray[$key]['farm_user_id_type'] =  $farmUserIdType;
                $instalmentsArray[$key]['type'] = 'Collection';
            }
            $seedlingPurchase->installments()->createManyQuietly($instalmentsArray);
        }

        return redirect()->back();
    }

    public function edit(SeedlingPurchaseRequest $seedling_purchase_request)
    {
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $seedlingPurchaseRequest = $user->seedlingPurchaseRequests()->findOrFail($seedling_purchase_request->id);
        return view('seedling-purchase-requests/create-or-edit', [
            'page_title' => 'تعديل طلب شراء أشتال',
            'seedling_purchase_request' => $seedlingPurchaseRequest,
        ]);
    }

    public function update(SeedlingPurchaseRequest $seedling_purchase_request, UpdateSeedlingPurchaseRequest $request)
    {
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }

        $requestedBy = $request->farm_user;
        $requestedByType = FarmUser::class;
        $farmUserIdType = 'FarmUser';
        if($request->requestedby_type == 'nursery'){
            $requestedBy = $request->nursery_id;
            $requestedByType = Nursery::class;
            $farmUserIdType = 'Nursery';
        }

        $seedlingPurchaseRequest = $user->seedlingPurchaseRequests()->findOrFail($seedling_purchase_request->id);
        $seedlingPurchaseRequest->update([
            "farm_user_id" => $requestedBy,
            "seedling_service_id" => $request->seedling_service,
            "tray_count" => $request->tray_count,
            "price_per_tray" => $request->price_per_tray,
            'requestedby' => $requestedBy,
            'requestedby_type' => $requestedByType,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $seedling_purchase_request->installments()->delete();
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['farm_user_id'] =  $requestedBy;
                $instalmentsArray[$key]['farm_user_id_type'] =  $farmUserIdType;
                $instalmentsArray[$key]['type'] = 'Collection';
            }
            $seedlingPurchaseRequest->installments()->createManyQuietly($instalmentsArray);
        }
        return redirect()->back();
    }

    public function export(Request $request)
    {
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        return Excel::download(new SeedlingPurchaseRequestsExport, 'seedling-purchase-requests.xlsx');
    }

    public function destroy(SeedlingPurchaseRequest $seedling_purchase_request)
    {
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $seedlingPurchaseRequest = $user->seedlingPurchaseRequests()->findOrFail($seedling_purchase_request->id);
        $seedlingPurchaseRequest->delete();

        return redirect()->back()->with('status', 'تم الحذف بنجاح');
    }

    public function reserveRequestSharedSeedlings(Request $request){
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }

        $requestedBy = $user->nursery->id;
        $requestedByType = Nursery::class;

        $seedling = SeedlingService::findOrFail($request->seedling_service_id);
        SeedlingPurchaseRequest::create([
            "nursery_id" => $seedling->nursery_id,
            "nursery_user_id" => $seedling->nursery_user_id,
            "farm_user_id" => $requestedBy,
            "seedling_service_id" => $seedling->id,
            "tray_count" => $request->tray_count,
            "price_per_tray" => '10',
            'requestedby' => $requestedBy,
            'requestedby_type' => $requestedByType,
            'status' => 2,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);
        return response()->json([],200);
    }

    public function updateReserveRequestSeedlingsStatus(Request $request,SeedlingPurchaseRequest $seedling_purchase_request){
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $nursery = $user->nursery;

        if($seedling_purchase_request->nursery_id == $nursery->id ){
            $seedling_purchase_request->status = $request->status;
            $seedling_purchase_request->save();
        }
        return response()->json([],200);
    }
}
