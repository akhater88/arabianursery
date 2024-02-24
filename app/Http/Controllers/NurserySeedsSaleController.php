<?php

namespace App\Http\Controllers;

use App\Enums\NurserySeedsSaleStatuses;
use App\Exports\NurserySeedsSalesExport;
use App\Http\Filters\SeedlingServiceFilter;
use App\Http\Requests\NurserySeedsSaleRequest;
use App\Http\Requests\UpdateNurserySeedsSaleRequest;
use App\Models\NurserySeedsSale;
use App\Models\SeedlingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Maatwebsite\Excel\Facades\Excel;

class NurserySeedsSaleController extends Controller
{
    public function index(SeedlingServiceFilter $filters)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $nurserySeedsSales = $nursery->nurserySeedsSales()->with(['farmUser', 'seedType'])
            ->orderBy('id', 'DESC')
            ->filterBy($filters)
            ->paginate()
            ->withQueryString();
        return view('nursery-seeds-sales.index', [
            'page_title' => 'مبيعات بذور المشتل',
            'nursery_seeds_sales' => $nurserySeedsSales,
            'statuses' => NurserySeedsSaleStatuses::values(),
        ]);
    }

    public function show(NurserySeedsSale $nurserySeedsSale)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $nurserySeedsSale = $nursery->nurserySeedsSales()->findOrFail($nurserySeedsSale->id);
        return view('nursery-seeds-sales.show', [
            'page_title' => 'مبيعات بذور',
            'statuses' => NurserySeedsSaleStatuses::values(),
            'nursery_seeds_sale' => $nurserySeedsSale,
        ]);
    }

    public function create()
    {
        return view('nursery-seeds-sales/create', [
            'page_title' => 'اضافة مبيعات بذور',
            'statuses' => NurserySeedsSaleStatuses::values(),
        ]);
    }

    public function store(NurserySeedsSaleRequest $request)
    {
        $seeds_sale = $request->user()->nurserySeedsSales()->create([
            "farm_user_id" => $request->farm_user,
            "seed_type_id" => $request->seed_type,
            "nursery_id" => $request->user()->nursery->id,
            "seed_class" => $request->seed_class,
            "seed_count" => $request->seed_count,
            "price" => $request->price,
            "status" => $request->status,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
        ]);

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['type'] = 'Collection';
            }
            $seeds_sale->installments()->createManyQuietly($instalmentsArray);
        }

        return redirect()->back();
    }

    public function edit(NurserySeedsSale $nurserySeedsSale)
    {
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $nursery = $user->nursery;
        $nurserySeedsSale = $nursery->nurserySeedsSales()->findOrFail($nurserySeedsSale->id);
        return view('nursery-seeds-sales/edit', [
            'page_title' => 'تعديل مبيعات بذور',
            'statuses' => NurserySeedsSaleStatuses::values(),
            'nursery_seeds_sale' => $nurserySeedsSale,
        ]);
    }

    public function update(NurserySeedsSale $nurserySeedsSale, UpdateNurserySeedsSaleRequest $request)
    {
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $nursery = $user->nursery;
        $nurserySeedsSale = $nursery->nurserySeedsSales()->findOrFail($nurserySeedsSale->id);
        $nurserySeedsSale->update([
            "farm_user" => $request->farm_user,
            "price" => $request->price,
            "status" => $request->status,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
            'installments' => $request->payment_type == 'installments' ? collect($request->installments)->values() : null,
        ]);
        if($request->payment_type == 'installments' && !empty($request->installments)){
            $nurserySeedsSale->installments()->delete();
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['type'] = 'Collection';
            }
            $nurserySeedsSale->installments()->createManyQuietly($instalmentsArray);
        }
        return redirect()->back();
    }

    public function export(Request $request)
    {
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        return Excel::download(new NurserySeedsSalesExport, 'Nursery-Seeds-Sales.xlsx');
    }

    public function destroy(NurserySeedsSale $nurserySeedsSale, Request $request)
    {
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $nurserySeedsSale->delete();
        return redirect()->back()->with('status', 'تم الحذف بنجاح');
    }

    public function get(Request $request)
    {
        return NurserySeedsSale::personal()
            ->where('nursery_id', $request->user()->nursery->id)
            ->where('id', $request->id)
            ->firstOrFail();
    }

    public function search(Request $request)
    {
        $personal_nursery_seeds_sales_query = NurserySeedsSale::query()->with('seedType')->limit(7);

        if ($request->q) {
            $personal_nursery_seeds_sales_query->where('seed_class', 'like', "%{$request->q}%")
                ->orWhereRelation('seedType', 'name', 'like', "%{$request->q}%");
        }

        $personal_nursery_seeds_sales_query->where('nursery_id', $request->user()->nursery->id)->personal();

        return [
            'results' => $personal_nursery_seeds_sales_query->get()->map(fn($nursery_seeds_sale) => [
                'id' => $nursery_seeds_sale->id,
                'text' => $nursery_seeds_sale->option_name
            ])];
    }


    public function updateStatus(NurserySeedsSale $nurserySeedsSale, Request $request)
    {
        $nurserySeedsSale->update([
            'status' => $request->status
        ]);

        return [
            'success' => true
        ];
    }
}
