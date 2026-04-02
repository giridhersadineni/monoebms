<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureFlag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeatureFlagController extends Controller
{
    public function index(): View
    {
        $flags = FeatureFlag::orderBy('name')->get();

        return view('admin.feature-flags.index', compact('flags'));
    }

    public function toggle(FeatureFlag $featureFlag): RedirectResponse
    {
        $featureFlag->update(['enabled' => ! $featureFlag->enabled]);

        $state = $featureFlag->enabled ? 'enabled' : 'disabled';

        return back()->with('success', '"' . $featureFlag->label . '" ' . $state . '.');
    }

    public function updateMessage(Request $request, FeatureFlag $featureFlag): RedirectResponse
    {
        $request->validate(['message' => 'nullable|string|max:500']);

        $featureFlag->update(['message' => $request->input('message') ?: null]);

        return back()->with('success', 'Maintenance message updated for "' . $featureFlag->label . '".');
    }
}
