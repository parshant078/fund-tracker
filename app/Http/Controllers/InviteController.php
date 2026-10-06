<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function index()
    {
        $invites = Invite::orderByDesc('id')->get();

        return view('invites.index', compact('invites'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string|max:100',
            'donor_name' => 'nullable|string|max:100',
        ]);
        $invite = Invite::generate($data['label']);
        if (! empty($data['donor_name'])) {
            $invite->update(['donor_name' => $data['donor_name']]);
        }

        return back()->with('ok', 'Invite code created: '.$invite->code);
    }

    public function toggle(Invite $invite)
    {
        $invite->update(['active' => ! $invite->active]);

        return back()->with('ok', 'Invite updated.');
    }

    public function destroy(Invite $invite)
    {
        $invite->delete();

        return back()->with('ok', 'Invite deleted.');
    }
}
