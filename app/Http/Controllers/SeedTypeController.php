<?php

namespace App\Http\Controllers;

use App\Models\SeedType;
use Illuminate\Http\Request;

class SeedTypeController extends Controller
{
    public function search(Request $request)
    {
        $seed_types_query = SeedType::query()->limit(7);

        if ($request->q) {
            $seed_types_query->where('name', 'like', "%{$request->q}%");
        }

        return [
            'results' => $seed_types_query->get()->map(fn($seed_type) => [
                'id' => $seed_type->id,
                'text' => $seed_type->name
            ])];
    }

    public function store(Request $request)
    {
        $request->validate([
            'seed_type_name' => ['required', 'string', 'max:255'],
        ]);

        return SeedType::create([
            'name' => $request->seed_type_name,
        ]);
    }
}
