<?php

namespace App\Http\Controllers;

use App\Http\Requests\NurseryFarmerSeasonReportRequest;
use App\Models\FarmUser;
use App\Models\Installment;
use App\Models\Season;
use App\Models\SeedlingPurchaseRequest;
use App\Models\SeedlingService;
use App\Models\NurserySeedsSale;
use App\Models\NurseryWarehouseEntity;

class NurseryFarmerSeasonReportController extends Controller
{
    /**
     * Display the printable financial report for a nursery farmer.
     */
    public function __invoke(NurseryFarmerSeasonReportRequest $request, $farmer)
    {
        $user = $request->user();
        $nursery = $user->nursery;

        if (! $user->hasRole('nursery-admin')) {
            abort(403);
        }

        $farmer = FarmUser::withTrashed()->findOrFail($farmer);

        if (! $nursery || ! $nursery->farmUsers()->withTrashed()->whereKey($farmer->getKey())->exists()) {
            abort(404);
        }

        $seasons = $nursery->definedSeasons()->orderByDesc('start_date')->get();
        $seasonInput = $request->validated('season_id');
        $selectedSeason = null;
        $seasonFilter = null;

        if ($seasonInput && $seasonInput !== 'all') {
            $seasonFilter = (int) $seasonInput;
            $selectedSeason = $seasons->firstWhere('id', $seasonFilter);
        }

        $installmentsQuery = $nursery->installments()
            ->with(['seasons'])
            ->where('farm_user_id', $farmer->getKey())
            ->where('farm_user_id_type', 'FarmUser')
            ->orderByDesc('invoice_date');

        if ($seasonFilter) {
            $installmentsQuery->inSeason($seasonFilter);
        }

        $installments = $installmentsQuery->get();

        $installments->loadMorph('installmentable', [
            SeedlingService::class => function ($query) {
                $query->withTrashed()->with(['seedType']);
            },
            NurserySeedsSale::class => function ($query) {
                $query->withTrashed()->with(['seedType']);
            },
            SeedlingPurchaseRequest::class => function ($query) {
                $query->withTrashed()->with(['seedType']);
            },
            NurseryWarehouseEntity::class => function ($query) {
                $query->withTrashed()->with(['seedType']);
            },
        ]);

        $totals = [
            'overall' => $installments->sum('amount'),
            'collected' => $installments->whereNotNull('invoice_number')->sum('amount'),
            'pending' => $installments->whereNull('invoice_number')->sum('amount'),
        ];

        $seasonBreakdown = collect();

        $installments->each(function (Installment $installment) use ($seasonBreakdown) {
            $seasonCollection = $installment->seasons;

            if ($seasonCollection->isEmpty()) {
                $current = $seasonBreakdown->get('unassigned', [
                    'name' => 'سجلات بدون موسم',
                    'overall' => 0,
                    'collected' => 0,
                    'pending' => 0,
                ]);

                $current['overall'] += $installment->amount;
                $current['collected'] += $installment->invoice_number ? $installment->amount : 0;
                $current['pending'] += $installment->invoice_number ? 0 : $installment->amount;

                $seasonBreakdown->put('unassigned', $current);

                return;
            }

            $seasonCollection->each(function (Season $season) use ($seasonBreakdown, $installment) {
                $seasonId = (string) $season->getKey();

                $current = $seasonBreakdown->get($seasonId, [
                    'name' => $season->name,
                    'overall' => 0,
                    'collected' => 0,
                    'pending' => 0,
                ]);

                $current['overall'] += $installment->amount;
                $current['collected'] += $installment->invoice_number ? $installment->amount : 0;
                $current['pending'] += $installment->invoice_number ? 0 : $installment->amount;

                $seasonBreakdown->put($seasonId, $current);
            });
        });

        $seasonBreakdown = $seasonBreakdown->sortBy('name');

        return view('reports.nursery-farmer-season', [
            'nursery' => $nursery,
            'farmer' => $farmer,
            'seasons' => $seasons,
            'selectedSeason' => $selectedSeason,
            'seasonFilter' => $seasonInput ?: 'all',
            'installments' => $installments,
            'totals' => $totals,
            'seasonBreakdown' => $seasonBreakdown,
        ]);
    }
}
