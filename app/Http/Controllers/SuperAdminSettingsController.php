<?php

namespace App\Http\Controllers;

use App\Support\AsepAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SuperAdminSettingsController extends Controller
{
    public function edit(): View
    {
        return view('super-admin-settings', ['asepEnabled' => AsepAvailability::enabled()]);
    }

    public function updateAsep(): RedirectResponse
    {
        $enabled = request()->validate(['enabled' => ['required', 'boolean']])['enabled'];

        DB::table('app_settings')->updateOrInsert(
            ['key' => 'asep_enabled'],
            ['value' => $enabled ? '1' : '0', 'updated_at' => now()]
        );

        return redirect()->route('super-admin.settings')->with('status', $enabled
            ? 'Aplikasi ASEP sudah diaktifkan.'
            : 'Aplikasi ASEP sudah dinonaktifkan.');
    }
}
