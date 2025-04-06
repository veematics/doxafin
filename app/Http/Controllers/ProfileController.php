<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function updatePassword(Request $request)
    {
        //\Log::info('Password update request:', $request->all());

        try {
            // First validate basic requirements
            $request->validate([
                'current_password' => ['required'],
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);

            $user = Auth::user();

            // Check if current password matches
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'error' => 'Current password is incorrect'
                ], 422);
            }

            // Update the password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'Password updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while updating password'
            ], 500);
        }
    }

    public function updateAvatar(Request $request)
    {
        try {
            $validated = $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:100']
            ], [
                'avatar.required' => 'Please select an image file.',
                'avatar.image' => 'The file must be an image.',
                'avatar.mimes' => 'Only JPG, PNG, and WEBP files are allowed.',
                'avatar.max' => 'Image size must not exceed 100KB.'
            ]);

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
                
                // Store directly in public directory
                $path = $avatar->move(public_path('images/avatars'), $filename);
                
                if (!$path) {
                    throw new \Exception('Failed to store avatar');
                }
                
                // Delete old avatar if exists and it's not the default
                $user = auth()->user();
                if ($user->avatar && $user->avatar !== 'avatar-default.svg') {
                    if (file_exists(public_path('images/avatars/' . $user->avatar))) {
                        unlink(public_path('images/avatars/' . $user->avatar));
                    }
                }
                
                // Update user avatar in database
                $user->update(['avatar' => $filename]);
                
                return response()->json([
                    'message' => 'Avatar updated successfully',
                    'avatar' => asset('images/avatars/' . $filename)
                ]);
            }
    
            return response()->json(['error' => 'No avatar file provided'], 400);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Invalid image file. Please ensure it is JPG, PNG, or WEBP and under 100KB.'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update avatar: ' . $e->getMessage()
            ], 500);
        }
    }
}
