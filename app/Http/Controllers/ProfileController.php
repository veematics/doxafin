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
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();
        
        // Update the password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:100']
        ]);
    
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            
            // Store the new avatar
            $avatar->storeAs('public/images/avatars', $filename);
            
            // Delete old avatar if exists
            if (auth()->user()->avatar) {
                Storage::delete('public/images/avatars/' . auth()->user()->avatar);
            }
            
            // Update user avatar in database
            auth()->user()->update(['avatar' => $filename]);
            
            return response()->json(['message' => 'Avatar updated successfully']);
        }
    
        return response()->json(['error' => 'No avatar file provided'], 400);
    }
}
