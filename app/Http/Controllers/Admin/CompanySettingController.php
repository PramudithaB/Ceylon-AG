<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quotation\UpdateCompanySettingsRequest;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    /**
     * Edit Company & Bank Settings.
     */
    public function edit()
    {
        $settings = CompanySetting::getSettings();

        return view('admin.quotations.settings', compact('settings'));
    }

    /**
     * Update Company & Bank Settings.
     */
    public function update(UpdateCompanySettingsRequest $request)
    {
        $settings = CompanySetting::getSettings();
        $validated = $request->validated();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path && $settings->logo_path !== 'images/logo.png' && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('company', 'public');
        }

        $settings->update($validated);

        flash_message('Company branding and bank details updated successfully!', 'success');

        return redirect()->route('admin.quotations.settings.edit');
    }
}
