<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Invite::orderByDesc('id')->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'label' => 'required|string|max:100',
            'donor_name' => 'nullable|string|max:100',
        ]);
        $invite = Invite::generate($data['label']);
        if (! empty($data['donor_name'])) {
            $invite->update(['donor_name' => $data['donor_name']]);
        }

        return response()->json(['data' => $invite->fresh()], 201);
    }

    public function toggle(Invite $invite): JsonResponse
    {
        $invite->update(['active' => ! $invite->active]);

        return response()->json(['data' => $invite->fresh()]);
    }

    public function destroy(Invite $invite): JsonResponse
    {
        $invite->delete();

        return response()->json(['message' => 'Invite deleted.']);
    }
}
