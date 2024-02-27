<?php

namespace App\Http\Controllers;

use App\Exports\SeedlingPurchaseRequestsExport;
use App\Http\Filters\SeedlingPurchaseRequestFilter;
use App\Http\Requests\StoreSeedlingPurchaseRequest;
use App\Http\Requests\UpdateSeedlingPurchaseRequest;
use App\Models\SeedlingPurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SeedlingPurchaseRequestController extends Controller
{
    public function index(SeedlingPurchaseRequestFilter $filters)
    {
        $user = Auth::user();

        $seedlingPurchaseRequest = $user->seedlingPurchaseRequests()->with(['farmUser', 'seedlingService.seedType'])
            ->filterBy($filters)
            ->paginate()
            ->withQueryString();
        return view('seedling-purchase-requests.index', [
            'page_title' => 'مبيعات اشتال خاصة مشتل',
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
        $seedlingPurchase = $request->user()->seedlingPurchaseRequests()->create([
            "nursery_id" => $request->user()->nursery->id,
            "farm_user_id" => $request->farm_user,
            "seedling_service_id" => $request->seedling_service,
            "tray_count" => $request->tray_count,
            "price_per_tray" => $request->price_per_tray,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['farm_user_id'] =  $request->farm_user;
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
        $seedlingPurchaseRequest = $user->seedlingPurchaseRequests()->findOrFail($seedling_purchase_request->id);
        $seedlingPurchaseRequest->update([
            "farm_user_id" => $request->farm_user,
            "seedling_service_id" => $request->seedling_service,
            "tray_count" => $request->tray_count,
            "price_per_tray" => $request->price_per_tray,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $seedling_purchase_request->installments()->delete();
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['farm_user_id'] =  $seedlingPurchaseRequest->farm_user_id;
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
}
