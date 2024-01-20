<?php

namespace App\Http\Controllers;

use App\Exports\SeedlingPurchaseRequestsExport;
use App\Http\Filters\SeedlingPurchaseRequestFilter;
use App\Http\Requests\StoreSeedlingPurchaseRequest;
use App\Http\Requests\UpdateSeedlingPurchaseRequest;
use App\Models\SeedlingPurchaseRequest;
use Maatwebsite\Excel\Facades\Excel;

class SeedlingPurchaseRequestController extends Controller
{
    public function index(SeedlingPurchaseRequestFilter $filters)
    {
        return view('seedling-purchase-requests.index', [
            'page_title' => 'مبيعات اشتال خاصة مشتل',
            'seedling_purchase_requests' => SeedlingPurchaseRequest::with(['farmUser', 'seedlingService.seedType'])
                ->filterBy($filters)
                ->paginate()
                ->withQueryString(),
        ]);
    }

    public function show(SeedlingPurchaseRequest $seedling_purchase_request)
    {
        return view('seedling-purchase-requests.show', [
            'page_title' => 'تعديل طلب شراء أشتال',
            'seedling_purchase_request' => $seedling_purchase_request,
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
                $instalmentsArray[$key]['type'] = 'Collection';
            }
            $seedlingPurchase->installments()->createManyQuietly($instalmentsArray);
        }

        return redirect()->back();
    }

    public function edit(SeedlingPurchaseRequest $seedling_purchase_request)
    {
        return view('seedling-purchase-requests/create-or-edit', [
            'page_title' => 'تعديل طلب شراء أشتال',
            'seedling_purchase_request' => $seedling_purchase_request,
        ]);
    }

    public function update(SeedlingPurchaseRequest $seedling_purchase_request, UpdateSeedlingPurchaseRequest $request)
    {
        $seedling_purchase_request->update([
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
                $instalmentsArray[$key]['type'] = 'Collection';
            }
            $seedling_purchase_request->installments()->createManyQuietly($instalmentsArray);
        }
        return redirect()->back();
    }

    public function export()
    {
        return Excel::download(new SeedlingPurchaseRequestsExport, 'seedling-purchase-requests.xlsx');
    }

    public function destroy(SeedlingPurchaseRequest $seedling_purchase_request)
    {
        $seedling_purchase_request->delete();

        return redirect()->back()->with('status', 'تم الحذف بنجاح');
    }
}
