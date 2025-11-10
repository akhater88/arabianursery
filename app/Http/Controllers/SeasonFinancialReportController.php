<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonFinancialReportRequest;
use App\Models\Installment;
use App\Models\NurserySeedsSale;
use App\Models\NurseryWarehouseEntity;
use App\Models\Season;
use App\Models\SeedlingPurchaseRequest;
use App\Models\SeedlingService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class SeasonFinancialReportController extends Controller
{
    public function __invoke(SeasonFinancialReportRequest $request): View
    {
        $user = $request->user();
        $nursery = $user->nursery;

        $seasons = Season::forNursery($nursery)
            ->orderByDesc('start_date')
            ->get();

        $validated = $request->validated();
        $seasonFilter = $validated['season_id'] ?? null;
        $showingAllSeasons = $seasonFilter === 'all';

        $selectedSeason = null;

        if (! $showingAllSeasons) {
            $selectedSeason = $seasons->firstWhere('id', $seasonFilter) ?? $seasons->first();
            $seasonFilter = $selectedSeason?->id;
        }

        $salesTotal = 0.0;
        $cashCollected = 0.0;
        $installmentsPaid = 0.0;
        $installmentsPending = 0.0;
        $farmerInstallments = collect();

        if ($showingAllSeasons || $selectedSeason) {
            $salesQuery = $nursery->nurserySeedsSales()
                ->with('farmUser');

            if ($selectedSeason) {
                $salesQuery->inSeason($selectedSeason);
            }

            $sales = $salesQuery->get();

            $salesTotal = (float) $sales->sum('price');
            $cashCollected = (float) $sales->sum(fn ($sale) => (float) data_get($sale->cash, 'amount', 0));

            $installments = Installment::query()
                ->with('farmUser')
                ->where('nursery_id', $nursery->getKey())
                ->where('type', 'Collection')
                ->where('farm_user_id_type', 'FarmUser')
                ->whereHasMorph(
                    'installmentable',
                    [
                        NurserySeedsSale::class,
                        SeedlingService::class,
                        SeedlingPurchaseRequest::class,
                        NurseryWarehouseEntity::class,
                    ],
                    function ($query) use ($selectedSeason) {
                        if ($selectedSeason) {
                            $query->inSeason($selectedSeason);
                        }
                    }
                )
                ->get();

            $installmentsPaid = (float) $installments
                ->whereNotNull('invoice_number')
                ->sum('amount');

            $installmentsPending = (float) $installments
                ->whereNull('invoice_number')
                ->sum('amount');

            $farmerInstallments = $installments
                ->groupBy('farm_user_id')
                ->reject(fn (Collection $items, $farmerId) => is_null($farmerId))
                ->map(function (Collection $items) {
                    $farmer = $items->first()->farmUser;

                    return [
                        'farmer' => $farmer,
                        'paid' => (float) $items->whereNotNull('invoice_number')->sum('amount'),
                        'pending' => (float) $items->whereNull('invoice_number')->sum('amount'),
                    ];
                })
                ->sortBy(fn (array $row) => optional($row['farmer'])->name ?? '');
        }

        return view('reports.season-financial', [
            'page_title' => 'التقرير المالي الموسمي',
            'seasons' => $seasons,
            'selectedSeason' => $selectedSeason,
            'seasonFilter' => $seasonFilter ?? ($showingAllSeasons ? 'all' : null),
            'showingAllSeasons' => $showingAllSeasons,
            'totals' => [
                'sales' => $salesTotal,
                'cash' => $cashCollected,
                'installments_paid' => $installmentsPaid,
                'installments_pending' => $installmentsPending,
            ],
            'farmerInstallments' => $farmerInstallments,
        ]);
    }
}
