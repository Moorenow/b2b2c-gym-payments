<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = Plan::query()
            ->orderBy('tier')
            ->orderBy('frequency')
            ->get();

        return response()->json($plans);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedData($request);
        $plan = Plan::create($data);

        return response()->json($plan, 201);
    }

    public function show(Plan $plan): JsonResponse
    {
        return response()->json($plan);
    }

    public function update(Request $request, Plan $plan): JsonResponse
    {
        $data = $this->validatedData($request, $plan);
        $plan->update($data);

        return response()->json($plan);
    }

    public function destroy(Plan $plan): JsonResponse
    {
        $plan->delete();

        return response()->json(['message' => 'Plan eliminado.']);
    }

    private function validatedData(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tier' => ['required', Rule::in(Plan::TIERS)],
            'frequency' => ['required', Rule::in(Plan::FREQUENCIES)],
            'base_price' => ['nullable', 'integer', 'min:0'],
            'discount_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
