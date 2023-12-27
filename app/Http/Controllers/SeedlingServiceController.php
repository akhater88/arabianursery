<?php

namespace App\Http\Controllers;

use App\Enums\SeedlingServiceStatuses;
use App\Http\Requests\StoreSeedlingServiceRequest;
use App\Http\Requests\UpdateSeedlingServiceRequest;
use App\Models\SeedlingService;
use Illuminate\Http\Request;

class SeedlingServiceController extends Controller
{
    public function create()
    {
        return view('seedling-services/create', [
            'page_title' => 'إضافة خدمة تشتيل',
            'statuses' => SeedlingServiceStatuses::values(),
        ]);
    }

    public function store(StoreSeedlingServiceRequest $request)
    {
        $request->user()->seedlingServices()->create([
            "type" => $request->type,
            "farm_user_id" => $request->type == SeedlingService::TYPE_FARMER ? $request->farm_user : null,
            "tray_count" => $request->tray_count,
            "seed_type_id" => $request->seed_type,
            "nursery_id" => $request->user()->nursery->id,
            "seed_class" => $request->seed_class,
            "seed_count" => $request->seed_count,
            "germination_rate" => $request->germination_rate,
            "germination_period" => $request->germination_period,
            "greenhouse_number" => $request->greenhouse_number,
            "tunnel_greenhouse_number" => $request->tunnel_greenhouse_number,
            "price_per_tray" => $request->price_per_tray,
            "additional_cost" => $request->additional_cost,
            "status" => $request->status,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
        ]);

        return redirect()->back();
    }

    public function edit(SeedlingService $seedling_service)
    {
        return view('seedling-services/edit', [
            'page_title' => 'تعديل خدمة تشتيل',
            'statuses' => SeedlingServiceStatuses::values(),
            'seedling_service' => $seedling_service,
        ]);
    }

    public function update(SeedlingService $seedling_service, UpdateSeedlingServiceRequest $request)
    {
        $seedling_service->update([
            "tray_count" => $request->tray_count,
            "germination_rate" => $request->germination_rate,
            "germination_period" => $request->germination_period,
            "greenhouse_number" => $request->greenhouse_number,
            "tunnel_greenhouse_number" => $request->tunnel_greenhouse_number,
            "price_per_tray" => $request->price_per_tray,
            "additional_cost" => $request->additional_cost,
            "status" => $request->status,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
        ]);

        return redirect()->back();
    }

    public function get(Request $request)
    {
        return SeedlingService::with('seedlingPurchaseRequests')
            ->personal()
            ->where('nursery_id', $request->user()->nursery->id)
            ->where('id', $request->id)
            ->firstOrFail();
    }

    public function search(Request $request)
    {
        $personal_seedling_service_query = SeedlingService::query()->with('seedType')->limit(7);

        if ($request->q) {
            $personal_seedling_service_query->where('seed_class', 'like', "%{$request->q}%")
                ->orWhereRelation('seedType', 'name', 'like', "%{$request->q}%");
        }

        $personal_seedling_service_query->where('nursery_id', $request->user()->nursery->id)->personal();

        return [
            'results' => $personal_seedling_service_query->get()->map(fn($seedling_service) => [
                'id' => $seedling_service->id,
                'text' => $seedling_service->option_name
            ])];
    }
}
