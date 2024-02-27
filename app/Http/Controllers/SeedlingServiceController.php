<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Enums\SeedlingServiceStatuses;
use App\Exports\SeedlingServicesExport;
use App\Http\Filters\SeedlingServiceFilter;
use App\Http\Requests\StoreSeedlingServiceRequest;
use App\Http\Requests\UpdateSeedlingServiceRequest;
use App\Models\FarmUser;
use App\Models\SeedlingService;
use App\Notifications\SeedlingServiceCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;
use Maatwebsite\Excel\Facades\Excel;


class SeedlingServiceController extends Controller
{

    public function index(SeedlingServiceFilter $filters)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $seedlingServices = $nursery->seedlingServices()->with(['farmUser' => fn($q) => $q->withTrashed(), 'seedType'])
            ->orderBy('id', 'DESC')
            ->filterBy($filters)
            ->paginate()
            ->withQueryString();
        return view('seedling-services.index', [
            'page_title' => 'خدمات التشتيل',
            'seedling_services' => $seedlingServices,
            'statuses' => SeedlingServiceStatuses::values(),
        ]);
    }

    public function show(SeedlingService $seedling_service)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $seedlingService = $nursery->seedlingServices()->with(['farmUser' => fn($q) => $q->withTrashed()])->findOrFail($seedling_service->id);
        return view('seedling-services.show', [
            'page_title' => 'خدمة تشتيل',
            'statuses' => SeedlingServiceStatuses::values(),
            'seedling_service' => $seedlingService,
        ]);
    }

    public function create()
    {
        return view('seedling-services/create', [
            'page_title' => 'إضافة خدمة تشتيل',
            'statuses' => SeedlingServiceStatuses::values(),
        ]);
    }

    public function store(StoreSeedlingServiceRequest $request)
    {
        $seedlingService = $request->user()->seedlingServices()->with(['farmUser' => fn($q) => $q->withTrashed()])->create([
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
            "discount_amount" => $request->discount_amount,
            "status" => $request->status,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);



        if($request->payment_type == 'installments' && !empty($request->installments)){
            $instalmentsArray = [];
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['farm_user_id'] =  $request->type == SeedlingService::TYPE_FARMER ? $request->farm_user : null;
                $instalmentsArray[$key]['type'] = 'Collection';
            }
            $seedlingService->installments()->createManyQuietly($instalmentsArray);
        }
        if($request->type == SeedlingService::TYPE_FARMER){
            $farmerUser = FarmUser::find($request->farm_user);
            if($farmerUser->email){
                $farmerUser->notify(new SeedlingServiceCreated($seedlingService));
            }

            $title = "تم إضافة أشتال";
            $body = $seedlingService->seedType->name." ".$seedlingService->seed_class.":";
            Helpers::sendNotification($farmerUser, $title, $body, ['seedling_id' => $seedlingService->id, 'type' => 'seedling'] );

        }

        return redirect()->back();
    }

    public function edit(SeedlingService $seedling_service)
    {
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $nursery = $user->nursery;
        $seedlingService = $nursery->seedlingServices()->with(['farmUser' => fn($q) => $q->withTrashed()])->findOrFail($seedling_service->id);
        return view('seedling-services/edit', [
            'page_title' => 'تعديل خدمة تشتيل',
            'statuses' => SeedlingServiceStatuses::values(),
            'seedling_service' => $seedlingService,
        ]);
    }

    public function update(SeedlingService $seedling_service, UpdateSeedlingServiceRequest $request)
    {

        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $title = "تم تعديل خدمة تشتيل";
        $nursery = $user->nursery;
        $seedlingService = $nursery->seedlingServices()->with(['farmUser' => fn($q) => $q->withTrashed()])->findOrFail($seedling_service->id);
        $seedlingService->update([
            "tray_count" => $request->tray_count,
            "germination_rate" => $request->germination_rate,
            "germination_period" => $request->germination_period,
            "greenhouse_number" => $request->greenhouse_number,
            "tunnel_greenhouse_number" => $request->tunnel_greenhouse_number,
            "price_per_tray" => $request->price_per_tray,
            "additional_cost" => $request->additional_cost,
            "discount_amount" => $request->discount_amount,
            "status" => $request->status,
            "cash" => $request->payment_type == 'cash' ? ['invoice_number' => $request->cash_invoice_number, 'amount' => $request->cash_amount] : null,
        ]);
        $body = $seedlingService->seedType->name." ".$seedlingService->seed_class.":";
        $isUpdated = $seedling_service->syncImages($request->images);

        if($isUpdated){
            $body .= 'تم إضافة صور ';
        }

        if($request->payment_type == 'installments' && !empty($request->installments)){
            $countInstalmentsBefore = $seedling_service->installments->count();
            $seedling_service->installments()->delete();
            $instalmentsArray = [];
            $countInstalmentsAfter = 0;
            foreach ($request->installments as $key => $value ){
                $instalmentsArray[$key] = $value;
                $instalmentsArray[$key]['nursery_id'] = $request->user()->nursery->id;
                $instalmentsArray[$key]['farm_user_id'] =  $seedlingService->farm_user_id;
                $instalmentsArray[$key]['type'] = 'Collection';
                $countInstalmentsAfter++;
            }
            if($countInstalmentsBefore != $countInstalmentsAfter){
                $body .= 'تم تعديل الدفعات ';
            }
            $seedling_service->installments()->createManyQuietly($instalmentsArray);

        } elseif($request->payment_type == 'cash'){
            $seedling_service->installments()->delete();
            $body .= 'تم تعديل طريقة الدفع الى كاش ';
        }

        if($seedlingService->type == SeedlingService::TYPE_FARMER){
            $farmerUser = $seedlingService->farmUser;
            Helpers::sendNotification($farmerUser, $title, $body, ['seedling_id' => $seedlingService->id, 'type' => 'seedling'] );
        }

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $user = $request->user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        return Excel::download(new SeedlingServicesExport, 'seedling-services.xlsx');
    }

    public function destroy(SeedlingService $seedling_service)
    {
        $user = Auth::user();
        if(!$user->hasRole('nursery-admin')){
            return abort(403);
        }
        $nursery = $user->nursery;
        $seedlingService = $nursery->seedlingServices()->findOrFail($seedling_service->id);
        $seedlingService->delete();

        return redirect()->back()->with('status', 'تم الحذف بنجاح');
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
        $user = Auth::user();
        $nursery = $user->nursery;
        $personal_seedling_service_query = SeedlingService::query()->with('seedType')->where('nursery_id',$nursery->id)->limit(7);

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

    public function storeMedia(Request $request)
    {
        $request->validate([
           'file' => [
               File::types(['jpeg','jpg','png'])
                   ->max(2 * 1024),
           ]
        ]);

        $file = $request->file('file');

        $file->store('tmp/uploads');

        return response()->json([
            'name'          => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function updateStatus(SeedlingService $seedling_service, Request $request)
    {
        $user = Auth::user();
        $nursery = $user->nursery;
        $seedlingService = $nursery->seedlingServices()->findOrFail($seedling_service->id);
        $seedlingService->update([
            'status' => $request->status
        ]);

        return [
            'success' => true
        ];
    }
}
