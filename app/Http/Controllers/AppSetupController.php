<?php

namespace App\Http\Controllers;

use App\Models\AppSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AppSetupController extends Controller
{
    public function index()
    {
        $appSetup = AppSetup::firstOrCreate(
            ['AppsID' => 1],
            [
                'AppsName' => 'DoxaApp',
                'AppsTitle' => 'Doxa Application',
                'AppsSubTitle' => 'Enterprise Application',
            ]
        );
        return view('appsetting.appsetup.index', compact('appSetup'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'AppsName' => 'required',
            'AppsTitle' => 'required',
            'AppsLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'AppsShortLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
        ]);

        $appSetup = AppSetup::findOrFail(1);
        
        // Update basic information
        $updateData = [
            'AppsName' => $request->AppsName,
            'AppsTitle' => $request->AppsTitle,
            'AppsSubTitle' => $request->AppsSubTitle,
            'AppsLogo' => $appSetup->AppsLogo, // Keep existing logo if no new upload
            'AppsShortLogo' => $appSetup->AppsShortLogo // Keep existing short logo if no new upload
        ];

        // Only process logo if new file is uploaded
        if ($request->hasFile('AppsLogo')) {
            try {
                if ($appSetup->AppsLogo && Storage::exists('public/images/app/' . $appSetup->AppsLogo)) {
                    Storage::delete('public/images/app/' . $appSetup->AppsLogo);
                }
                $logoName = time() . '_logo.' . $request->AppsLogo->extension();
                // Store directly in the public disk
                $path = Storage::disk('public')->putFileAs(
                    'images/app',
                    $request->file('AppsLogo'),
                    $logoName
                );
                
                Log::info('Logo upload attempt', [
                    'filename' => $logoName,
                    'path' => $path,
                    'success' => (bool)$path
                ]);
                
                if (!$path) {
                    throw new \Exception('Failed to store logo');
                }
                
                $updateData['AppsLogo'] = $logoName;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload logo: ' . $e->getMessage());
            }
        }

        // Only process short logo if new file is uploaded
        if ($request->hasFile('AppsShortLogo')) {
            try {
                if ($appSetup->AppsShortLogo && Storage::exists('public/images/app/' . $appSetup->AppsShortLogo)) {
                    Storage::delete('public/images/app/' . $appSetup->AppsShortLogo);
                }
                $shortLogoName = time() . '_short.' . $request->AppsShortLogo->extension();
                // Store directly in the public disk
                $path = Storage::disk('public')->putFileAs(
                    'images/app',
                    $request->file('AppsShortLogo'),
                    $shortLogoName
                );
                
                Log::info('Short logo upload attempt', [
                    'filename' => $shortLogoName,
                    'path' => $path,
                    'success' => (bool)$path
                ]);
                
                if (!$path) {
                    throw new \Exception('Failed to store short logo');
                }
                
                $updateData['AppsShortLogo'] = $shortLogoName;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload short logo: ' . $e->getMessage());
            }
        }

        $appSetup->update($updateData);

        return redirect()->route('appsetting.appsetup.index')
            ->with('success', 'Application settings updated successfully');
    }
}