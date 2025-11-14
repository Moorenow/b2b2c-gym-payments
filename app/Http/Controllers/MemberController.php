<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(): JsonResponse
    {
        $members = Member::with('plan:id,name,tier,frequency,final_price')
            ->orderByDesc('membership_start_date')
            ->get();

        return response()->json($members);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedData($request);
        $member = Member::create($data);
        $member->load('plan:id,name,tier,frequency,final_price');

        return response()->json($member, 201);
    }

    public function show(Member $member): JsonResponse
    {
        return response()->json($member->load('plan:id,name,tier,frequency,final_price'));
    }

    public function update(Request $request, Member $member): JsonResponse
    {
        $data = $this->validatedData($request, $member);
        $member->update($data);
        $member->load('plan:id,name,tier,frequency,final_price');

        return response()->json($member);
    }

    public function destroy(Member $member): JsonResponse
    {
        $member->delete();

        return response()->json(['message' => 'Miembro eliminado.']);
    }

    private function validatedData(Request $request, ?Member $member = null): array
    {
        return $request->validate([
            'plan_id' => ['required', Rule::exists(Plan::class, 'id')],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'identification_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('members', 'identification_number')->ignore($member?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('members', 'email')->ignore($member?->id),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_phone' => ['required', 'string', 'max:255'],
            'membership_start_date' => ['required', 'date'],
            'status' => ['required', Rule::in(Member::STATUSES)],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
