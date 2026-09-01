<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteProtectionController extends Controller
{
    public function edit()
    {
        return view('admin.site-protection.edit', [
            'disableRightClick' => SiteSetting::bool('disable_right_click', true),
            'disableCopy' => SiteSetting::bool('disable_copy', true),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'disable_right_click' => ['nullable', 'boolean'],
            'disable_copy' => ['nullable', 'boolean'],
        ]);

        SiteSetting::set('disable_right_click', $request->boolean('disable_right_click'));
        SiteSetting::set('disable_copy', $request->boolean('disable_copy'));

        return redirect()
            ->route('admin.site-protection.edit')
            ->with('success', 'Site protection settings updated successfully.');
    }
}
