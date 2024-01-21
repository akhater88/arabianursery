<?php

namespace App\Http\Controllers;

use App\Exports\NurseryWarehouseEntitiesExport;
use App\Http\Filters\NurseryWareHouseEntityFilter;
use App\Http\Requests\StoreNurseryWarehouseEntityRequest;
use App\Http\Requests\UpdateNurseryWarehouseEntityRequest;
use App\Models\EntityType;
use App\Models\NurseryWarehouseEntity;
use App\Models\SeedType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class NurseryWarehouseEntityController extends Controller
{
    public function index(NurseryWareHouseEntityFilter $filters)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $nurseryWarehouseEntities = $nursery->nurseryWarehouseEntities()->with(['agriculturalSupplyStoreUser', 'entity'])
            ->orderBy('id', 'DESC')
            ->filterBy($filters)
            ->paginate()
            ->withQueryString();
        return view('warehouse-entities.index', [
            'page_title' => 'إدارة المخزن',
            'nursery_warehouse_entities' => $nurseryWarehouseEntities,
        ]);
    }

    public function show(NurseryWarehouseEntity $nursery_warehouse_entity)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $nurseryWarehouseEntity = $nursery->nurseryWarehouseEntities()->findOrFail($nursery_warehouse_entity->id);
        return view('warehouse-entities.show', [
            'page_title' => 'مدخل إلى المخزن',
            'entity_types' => EntityType::get(),
            'nursery_warehouse_entity' => $nurseryWarehouseEntity,
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
        $warehouseEntity = $request->user()->warehouseEntities()->create([
            "agricultural_supply_store_user_id" => $request->agricultural_supply_store_user,
            "entity_type_id" => $request->entity_type,
            "quantity" => $request->quantity,
            "price" => $request->price,
            'entity_type' => SeedType::class,
            'entity_id' => $request->seed_type,
            "seed_type_id" => $request->seed_type,
            "nursery_id" => $request->user()->nursery->id,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['type'] = 'Due';
            }
            $warehouseEntity->installments()->createManyQuietly($instalmentsArray);
        }

        return redirect()->back();
    }

    public function edit(NurseryWarehouseEntity $nursery_warehouse_entity)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $nurseryWarehouseEntity = $nursery->nurseryWarehouseEntities()->findOrFail($nursery_warehouse_entity->id);
        return view('warehouse-entities/create-or-edit', [
            'page_title' => 'تعديل طلب إدخال إلى المخزن',
            'entity_types' => EntityType::get(),
            'nursery_warehouse_entity' => $nurseryWarehouseEntity,
        ]);
    }

    public function update(NurseryWarehouseEntity $nursery_warehouse_entity, UpdateNurseryWarehouseEntityRequest $request)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $nurseryWarehouseEntity = $nursery->nurseryWarehouseEntities()->findOrFail($nursery_warehouse_entity->id);

        $nurseryWarehouseEntity->update([
                "agricultural_supply_store_user_id" => $request->agricultural_supply_store_user,
                "entity_type_id" => $request->entity_type,
                "quantity" => $request->quantity,
                "price" => $request->price,
                'entity_type' => SeedType::class,
                'entity_id' => $request->seed_type,
                "seed_type_id" => $request->seed_type,
                "nursery_id" => $request->user()->nursery->id,
                "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            ]
        );

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $nurseryWarehouseEntity->installments()->delete();
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['type'] = 'Due';
            }
            $nurseryWarehouseEntity->installments()->createManyQuietly($instalmentsArray);
        }

        return redirect()->back();
    }

    public function export()
    {
        return Excel::download(new NurseryWarehouseEntitiesExport, 'nursery-warehouse-entities.xlsx');
    }

    public function destroy(NurseryWarehouseEntity $nursery_warehouse_entity)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $nurseryWarehouseEntity = $nursery->nurseryWarehouseEntities()->findOrFail($nursery_warehouse_entity->id);
        $nurseryWarehouseEntity->delete();
        return redirect()->back()->with('status', 'تم الحذف بنجاح');
    }
}
