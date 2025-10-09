<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeasonRequest;
use App\Http\Requests\UpdateSeasonRequest;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeasonController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $this->ensureNurseryAdmin($user);

        $seasons = Season::forNursery($user->nursery)
            ->orderByDesc('start_date')
            ->paginate(10)
            ->withQueryString();

        return view('seasons.index', [
            'page_title' => 'المواسم',
            'seasons' => $seasons,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $this->ensureNurseryAdmin($user);

        return view('seasons.create', [
            'page_title' => 'إضافة موسم',
        ]);
    }

    public function store(StoreSeasonRequest $request)
    {
        $user = $request->user();
        $this->ensureNurseryAdmin($user);

        $user->nursery->definedSeasons()->create($request->validated());

        return redirect()
            ->route('seasons.index')
            ->with('status', 'تم إضافة الموسم بنجاح');
    }

    public function edit(Season $season)
    {
        $season = $this->resolveSeason($season);

        return view('seasons.edit', [
            'page_title' => 'تعديل موسم',
            'season' => $season,
        ]);
    }

    public function update(UpdateSeasonRequest $request, Season $season)
    {
        $season = $this->resolveSeason($season);
        $season->update($request->validated());

        return redirect()
            ->route('seasons.index')
            ->with('status', 'تم تحديث الموسم بنجاح');
    }

    public function destroy(Season $season)
    {
        $season = $this->resolveSeason($season);
        $season->delete();

        return redirect()
            ->route('seasons.index')
            ->with('status', 'تم حذف الموسم بنجاح');
    }

    protected function ensureNurseryAdmin($user): void
    {
        if (! $user || ! $user->hasRole('nursery-admin')) {
            abort(403);
        }
    }

    protected function resolveSeason(Season $season): Season
    {
        $user = Auth::user();
        $this->ensureNurseryAdmin($user);

        $nurseryId = $user->nursery?->getKey();

        if ($season->nursery_id !== $nurseryId) {
            abort(404);
        }

        return $season;
    }
}
