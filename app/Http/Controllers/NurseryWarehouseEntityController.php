<?php

namespace App\Http\Controllers;

use App\Exports\NurseryWarehouseEntitiesExport;
use App\Http\Filters\NurseryWareHouseEntityFilter;
use App\Http\Requests\StoreNurseryWarehouseEntityRequest;
use App\Http\Requests\UpdateNurseryWarehouseEntityRequest;
use App\Models\EntityType;
use App\Models\NurseryWarehouseEntity;
use App\Models\SeedType;
use Maatwebsite\Excel\Facades\Excel;

class NurseryWarehouseEntityController extends Controller
{
    public function index(NurseryWareHouseEntityFilter $filters)
    {
        return view('warehouse-entities.index', [
            'page_title' => 'إدارة المخزن',
            'nursery_warehouse_entities' => NurseryWarehouseEntity::with(['agriculturalSupplyStoreUser', 'entity'])
                ->filterBy($filters)
                ->paginate()
                ->withQueryString(),
        ]);
    }

    public function show(NurseryWarehouseEntity $nursery_warehouse_entity)
    {
        return view('warehouse-entities.show', [
            'page_title' => 'مدخل إلى المخزن',
            'entity_types' => EntityType::get(),
            'nursery_warehouse_entity' => $nursery_warehouse_entity,
        ]);
    }

    public function create()
    {
        return view('warehouse-entities/create-or-edit', [
            'page_title' => 'طلب إدخال إلى المخزن',
            'entity_types' => EntityType::get(),
            'nursery_warehouse_entity' => null,
        ]);
    }

    public function store(StoreNurseryWarehouseEntityRequest $request)
    {
        $request->user()->warehouseEntities()->create([
            "agricultural_supply_store_user_id" => $request->agricultural_supply_store_user,
            "entity_type_id" => $request->entity_type,
            "quantity" => $request->quantity,
            "price" => $request->price,
            'entity_type' => SeedType::class,
            'entity_id' => $request->seed_type,
            "seed_type_id" => $request->seed_type,
            "nursery_id" => $request->user()->nursery->id,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
        ]);

        return redirect()->back();
    }

    public function edit(NurseryWarehouseEntity $nursery_warehouse_entity)
    {
        return view('warehouse-entities/create-or-edit', [
            'page_title' => 'تعديل طلب إدخال إلى المخزن',
            'entity_types' => EntityType::get(),
            'nursery_warehouse_entity' => $nursery_warehouse_entity,
        ]);
    }

    public function update(NurseryWarehouseEntity $nursery_warehouse_entity, UpdateNurseryWarehouseEntityRequest $request)
    {
        $nursery_warehouse_entity->update([
                "agricultural_supply_store_user_id" => $request->agricultural_supply_store_user,
                "entity_type_id" => $request->entity_type,
                "quantity" => $request->quantity,
                "price" => $request->price,
                'entity_type' => SeedType::class,
                'entity_id' => $request->seed_type,
                "seed_type_id" => $request->seed_type,
                "nursery_id" => $request->user()->nursery->id,
                "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
                'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
            ]
        );

        return redirect()->back();
    }

    public function export()
    {
        return Excel::download(new NurseryWarehouseEntitiesExport, 'nursery-warehouse-entities.xlsx');
    }

    public function destroy(NurseryWarehouseEntity $nursery_warehouse_entity)
    {
        $nursery_warehouse_entity->delete();

        return redirect()->back()->with('status', 'تم الحذف بنجاح');
    }
}
